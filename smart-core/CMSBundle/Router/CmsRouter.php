<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Router;

use SmartCore\CMSBundle\Entity\Folder;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

class CmsRouter
{
    use ContainerAwareTrait;

    /**
     * @var array|null
     */
    protected $router_data = null;

    /**
     * @param  mixed|null $obj Node, Folder, $folderId or NULL for current folder Id form cms.context.
     *
     * @return string
     */
    public function getPath($obj = null): string
    {
        return $this->container->get('cms.folder')->getUri($obj);
    }

    /**
     * Tries to match a URL path with a set of routes.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param string     $baseUrl
     * @param string     $slug The path info to be parsed (raw format, i.e. not urldecoded)
     * @param int        $type
     * @param array|null $options
     *
     * @return array  Массив следующего формата:
     *      [folders]: array
     *          {folder_id}: SmartCore\Bundle\CMSBundle\Entity\Folder
     *      [meta]: array
     *          [keywords]: string
     *          [description]: string
     *          [robots]: string
     *          [language]: string
     *          [author]: string
     *      [status]: int 200
     *      [redirect_to]: string null, // Для статуса 301.
     *      [template]: string "main"
     *      [node_route]: array
     *
     *          @todo
     *      [current_folder_id]: int
     *      [current_folder_path]: string
     *
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     *
     * @api
     */
    public function match($baseUrl, $slug = null, $type = HttpKernelInterface::MASTER_REQUEST, array $options = null): array
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em   = $this->container->get('doctrine.orm.entity_manager');
        $site = $this->container->get('cms.context')->getSite();

        if ($type === HttpKernelInterface::MASTER_REQUEST) {
            if (!empty($this->router_data)) {
                return $this->router_data;
            }
            \Profiler::start('Folder Routing');
        }

        $data = [
            'folders'       => [],
            'meta'          => [],
            'status'        => 200,
            'redirect_to'   => null, // Для статуса 301.
            'template'      => 'default',
            'node_routing'  => null,
            'current_folder_id'   => 1,
            'current_folder_path' => $baseUrl.'/',
        ];

        $folder          = null;
        $parent_folder   = null;
        $router_node_id  = null;
        $template_self   = null;
        $slug            = '/'.$slug; // @todo сделать проверку на наличие слеша перед путём, чтобы привести к виду, как $this->container->get('request')->getPathInfo()
        $path_parts      = explode('/', $slug);

        foreach ($path_parts as $key => $segment) {
            // Проверка строки запроса на допустимые символы.
            // @todo сделать проверку на разрешение круглых скобок.
            if (!empty($segment) and !preg_match('/^[\(\)a-zA-Z\sа-яА-ЯЁё_@0-9.-]*$/iu', $segment)) {
                $data['status'] = 404;
                break;
            }

            // Закончить работу, если имя папки пустое и папка не является корневой т.е. обрабатывается последняя запись в строке УРИ.
            if (0 != $key and '' == $segment and null == $router_node_id) {
                // @todo здесь надо делать обработчик "файла" т.е. папки с выставленным флагом "is_file".
                break;
            }

            $criteria = [
                'is_active'     => true,
                'uri_part'      => empty($segment) ? null : $segment,
                'parent_folder' => $parent_folder,
            ];

            if ($parent_folder === null) {
                $criteria['id'] = $site and $site->getRootFolder() ? $site->getRootFolder()->getId() : false;

                unset($criteria['uri_part']);
            }

            $folder = $em->getRepository(Folder::class)->findOneBy($criteria);

            if ($folder) {
                if ($folder->isDeleted()) {
                    $data['status'] = 404;

                    break;
                } else { // @todo if ($this->Permissions->isAllowed('folder', 'read', $folder->permissions)) {
                    if ($folder->getUriPart()) {
                        if ($folder->isFile()) {
                            $data['current_folder_path'] .= $folder->getUriPart();

                            if (isset($path_parts[$key + 1])) {
                                $data['status'] = 301;
                                $data['redirect_to'] = $data['current_folder_path'];
                            }
                        } else {
                            $data['current_folder_path'] .= $folder->getUriPart().'/';

                            if (!isset($path_parts[$key + 1])) {
                                $data['status'] = 301;
                                $data['redirect_to'] = $data['current_folder_path'];
                            }
                        }
                    }

                    if ($folder->getTemplateInheritable()) {
                        $data['template'] = $folder->getTemplateInheritable();
                    }

                    $parent_folder  = $folder;
                    $router_node_id = ($folder) ? $folder->getRouterNodeId() : null;
                    $template_self  = $folder->getTemplateSelf();

                    $data['folders'][$folder->getId()] = [
                        'title' => $folder->getTitle(),
                        'description' => $folder->getDescription(),
                        'permissions' => $folder->getPermissionsCache(),
                    ];
                    $data['current_folder_id'] = $folder->getId();
                    $data['meta'] = $folder->getMeta();

                    // В данной папке есть нода которой передаётся дальнейший парсинг URI.
                    if ($router_node_id !== null) {
                        // Выполняется часть URI парсером модуля и возвращается результат работы, в дальнейшем он будет передан самой ноде.
                        // @todo запрос ноды только для получения имени модуля не сосвсем красиво...
                        //       может быть как-то кешировать это дело, либо хранить имя модуля прямо в таблице папок,
                        //       например в виде массива router_node_id и router_node_module.
                        try {
                            $node = $this->container->get('cms.node')->get($router_node_id);

                            $node_slug = str_replace($data['current_folder_path'], '', substr('/'.$baseUrl.'/', 0, -1).$slug);

                            if ($node_slug !== '/' and ($node->isDeleted() or $node->isNotActive())) {
                                $data['status'] = 404;

                                break;
                            }

                            $data['node_routing'] = [
                                'node_id'    => $router_node_id,
                                'controller' => $node->getParams() + $this->matchModule($node->getController(), $node_slug),
                            ];

                            if (isset($options['nodes'][$router_node_id])) {
                                $data['node_routing']['controller']['options'] = $options['nodes'][$router_node_id];
                            }

                            // Если нода подключена в корневую папку, то не используется собственный шаблон папки.
                            $template_self = null;
                        } catch (ResourceNotFoundException $e) {
                            // Роутинг модуля не нашел запрошенного ресурса.
                        }
                    }
                }

                /*else { // @todo права доступа
                    $data['status'] = 403;
                }*/
            } elseif (empty($data['node_routing'])) {
                $data['status'] = 404;
            }
        }

        if ($template_self) {
            $data['template'] = $template_self;
        }

        if ($type === HttpKernelInterface::MASTER_REQUEST) {
            \Profiler::end('Folder Routing');
            $this->router_data = $data;
        }

        return $data;
    }

    /**
     * @param  string       $module
     * @param  string       $path
     * @param  Request|null $request
     *
     * @return array|null
     *
     * @throw ResourceNotFoundException
     */
    public function matchModule($controller, $path, Request $request = null): ?array
    {
        if (false === strpos($path, '/')) {
            $path = '/'.$path;
        }

        if ($this->container->has('cms.router_module.'.$controller)) {
            /** @var \Symfony\Component\Routing\Matcher\UrlMatcher $matcher */
            $matcher = $this->container->get('cms.router_module.'.$controller);
            if ($request) {
                $context = new RequestContext();
                $context->fromRequest(Request::createFromGlobals());
                $matcher->setContext($context);
            }

            return $matcher->match($path);
        }

        return null;
    }

    /**
     * @param  string $module
     * @param  string $path
     *
     * @return array|null
     */
    public function matchModuleAdmin($module, $path): ?array
    {
        return $this->container->get('cms.router_module.'.$module.'.admin')->match($path);
    }

    /**
     * @param  string       $module
     * @param  string       $path
     * @param  Request|null $request
     *
     * @return array|null
     */
    public function matchModuleApi($module, $path, Request $request = null): ?array
    {
        $shortName = substr(strtolower($module), 0, -12);

        if ($this->container->has('cms.router_module_api.'.$shortName)) {
            /** @var \Symfony\Component\Routing\Matcher\UrlMatcher $matcher */
            $matcher = $this->container->get('cms.router_module_api.'.$shortName);
            if ($request) {
                $context = new RequestContext();
                $context->fromRequest(Request::createFromGlobals());
                $matcher->setContext($context);
            }

            return $matcher->match($path);
        }

        return null;
    }

    /**
     * @param  mixed|null $obj
     *
     * @return RedirectResponse
     */
    public function redirect($obj = null): RedirectResponse
    {
        return new RedirectResponse($this->getPath($obj));
    }
}

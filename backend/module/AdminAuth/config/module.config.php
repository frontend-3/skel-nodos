<?php

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Admin_Controller_Auth',
                        'action' => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'welcome' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/welcome',
                            'defaults' => array(
                                'controller' => 'Admin_Controller_Auth',
                                'action' => 'welcome',
                            ),
                        ),
                    ),
                    'system_roles' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/roles',
                            'defaults' => array(
                                'controller' => 'Admin_Controller_Role',
                                'action' => 'index',
                            ),
                        ),

                        'may_terminate' => true,
                        'child_routes' => array(
                            'view' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'get',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'get' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/add[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_Role',
                                                'action' => 'view',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'update' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'post',
                                ),
                                'child_routes' => array(
                                    'post' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/add[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_Role',
                                                'action' => 'update',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'delete' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'get,post',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'get' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/delete[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_Role',
                                                'action' => 'delete',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                        )

                    ),
                    'system_users' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/system_users',
                            'constraints' => array(
                                #'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                #'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin_Controller_User',
                                'action' => 'index',
                            ),
                        ),

                        'may_terminate' => true,
                        'child_routes' => array(
                            'view' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'get',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'get' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/add[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_User',
                                                'action' => 'view',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'update' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'post',
                                ),
                                'child_routes' => array(
                                    'post' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/add[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_User',
                                                'action' => 'update',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'delete' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'get,post',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'get' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/delete[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_User',
                                                'action' => 'delete',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                        )

                    ),
                    'system_permissions' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/permissions',
                            #'constraints' => array(
                                #'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                #'id' => '[0-9]+',
                            #),
                            'defaults' => array(
                                'controller' => 'Admin_Controller_Permission',
                                'action' => 'index',
                            ),
                        ),

                        'may_terminate' => true,
                        'child_routes' => array(
                            'view' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'get',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'get' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/add[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_Permission',
                                                'action' => 'view',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'update' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'post',
                                ),
                                'child_routes' => array(
                                    'post' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/add[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_Permission',
                                                'action' => 'update',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                            'delete' => array(
                                'type' => 'method',
                                'options' => array(
                                    'verb' => 'get,post',
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'get' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/delete[/:id]',
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                            'defaults' => array(
                                                'controller' => 'Admin_Controller_Permission',
                                                'action' => 'delete',
                                            ),
                                        ),
                                    ),
                                )
                            ),
                        )                        
                    ),
                    'logout' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'Admin_Controller_Auth',
                                'action' => 'logOut',
                            ),
                        ),
                    ),
                )
            )
        ),
    ),
    'view_helpers' => array(
    ),
    'translator' => array(
        'locale' => 'en_EN',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),

    ),

    'controllers' => array(
        'invokables' => array(
            'Admin_Controller_Auth' => 'AdminAuth\Controller\AdminAuthController',
            'Admin_Controller_User' => 'AdminAuth\Controller\AdminUserController',
            'Admin_Controller_Role' => 'AdminAuth\Controller\AdminRoleController',
            'Admin_Controller_Permission' => 'AdminAuth\Controller\AdminPermissionController',
            'Admin_Controller_LoadAuth' => 'AdminAuth\Controller\AdminAuthLoadController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => (IN_PRODUCTION == false),
        'display_exceptions'       => (IN_PRODUCTION == false),
        'doctype' => 'HTML5',
        'not_found_template' => 'Admin_404',
        'exception_template' => 'Admin_Error',
        'template_map' => array(
            'Admin_Layout' => __DIR__ . '/../view/layout/layout.phtml',
            'Admin_404' => __DIR__ . '/../view/error/404.phtml',
            'Admin_Error' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),


        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'createSuperUser'=>array(
                    'options' => array(
                        'route' => 'createsuperuser',
                        'defaults' => array(
                            'controller' => 'Admin_Controller_LoadAuth',
                            'action' => 'createSuperUser'
                        )
                    )
                ),
                'updatePasswordSuperUserAction'=>array(
                    'options' => array(
                        'route' => 'updatepasswordsuperuser',
                        'defaults' => array(
                            'controller' => 'Admin_Controller_LoadAuth',
                            'action' => 'updatePasswordSuperUser'
                        )
                    )
                ),
                'updatePermsSuperUser'=>array(
                    'options' => array(
                        'route' => 'updatepermsuperuser',
                        'defaults' => array(
                            'controller' => 'Admin_Controller_LoadAuth',
                            'action' => 'updatePerms'
                        )
                    )
                ),
                'add_perms'=>array(
                    'options' => array(
                        'route' => 'add_perms',
                        'defaults' => array(
                            'controller' => 'Admin_Controller_LoadAuth',
                            'action' => 'perms'
                        )
                    )
                ),

                 'help'=>array(
                    'options' => array(
                        'route' => 'help',
                        'defaults' => array(
                            'controller' => 'Admin_Controller_LoadAuth',
                            'action' => 'help'
                        )
                    )
                ),
            ),
        ),
    ),
);

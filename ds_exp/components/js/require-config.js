var require = {
    baseUrl: "components/js",
    deps: ['jquery', 'bootstrap', 'modal_customize', 'pgui.sidebar', 'user-js'],
    paths: {
        'jquery': 'libs/jquery/jquery.min',
        'user-js': 'user',
        'moment': 'libs/moment',
        'bootstrap': 'libs/bootstrap',
        'amplify.store': 'libs/amplify.store',
        'bootbox.min': 'locales/bootbox_locale',
        'class': 'libs/class',
        'async': 'libs/async',
        'microevent': 'libs/microevent',
        'underscore': 'libs/underscore',
        'jquery.bind-first': 'libs/jquery/jquery.bind-first',
        'jquery.plainoverlay': 'libs/jquery/jquery.plainoverlay',
        'jquery.resize': 'libs/jquery/jquery.resize',
        'jquery.validate': 'libs/jquery/jquery.validate',
        'jquery.hotkeys': 'libs/jquery/jquery.hotkeys',
        'jquery.query': 'libs/jquery/jquery.query',
        'jquery.highlight': 'libs/jquery/jquery.highlight',
        'jquery.form': 'libs/jquery/jquery.form',
        'jquery.stickytableheaders': 'libs/jquery/jquery.stickytableheaders',
        'jquery.magnific-popup': 'libs/jquery/jquery.magnific-popup',
        'jquery.maskedinput': 'libs/jquery/jquery.maskedinput',
        'datepicker': 'libs/bootstrap-datetimepicker.min',
        'pgui.admin_panel': 'pgui.admin_panel',
        'mootools-core': 'libs/mootools-core',
        'jquery.tmpl': 'libs/jquery/jquery.tmpl',
        'knockout': 'libs/knockout',
        'ckeditor': 'libs/ckeditor/adapters/jquery'
    },
    shim: {
        'user-js': ['jquery', 'bootstrap'],
        'knockout': ['jquery.tmpl'],
        'bootstrap': ['jquery'],
        'datepicker': ['jquery', 'moment'],
        'jquery.tmpl': ['jquery'],
        'jquery.bind-first': ['jquery'],
        'jquery.plainoverlay': ['jquery'],
        'jquery.validate': ['jquery'],
        'jquery.hotkeys': ['jquery'],
        'jquery.resize': ['jquery'],
        'jquery.query': ['jquery'],
        'jquery.highlight': ['jquery'],
        'jquery.form': ['jquery'],
        'jquery.stickytableheaders': ['jquery'],
        'jquery.magnific-popup': ['jquery'],
        'jquery.maskedinput': ['jquery'],
        'async': {
            exports: 'async'
        },
        'underscore': {
            exports: '_'
        },
        'ckeditor' : {
            deps: ['jquery', 'libs/ckeditor/ckeditor']
        }
    }
};

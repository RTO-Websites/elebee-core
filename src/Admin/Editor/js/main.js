/**
 *
 */
(function ($) {
  'use strict';

  if (typeof CodeMirror === "undefined") {
    console.log('CodeMirror is missing!');
    return;
  }

  let cm,
    init,
    configCodeMirror,
    setupEvents,
    registerEvents,
    triggerFunction,
    fns = {};


  /**
   *
   */
  init = function () {
    // wait for Dom to be set
    window.addEventListener('DOMContentLoaded', function () {
      //CodeMirror
      configCodeMirror();
      window.editor = cm;
      // fill CodeMirror Ed with "rendered"/HTML-free content
      cm.setValue(wp.data.select("core/editor").getCurrentPost().content);
      // necesseary to prevent losing leading whitespace when saving unedited post:
      wp.data.dispatch('core/editor').editPost({content: cm.getValue()});
      wp.data.dispatch('core/editor').refreshPost();
      setupEvents();
      registerEvents();
    });


  };


  /**
   *
   */
  configCodeMirror = function () {

    cm = CodeMirror.fromTextArea(document.getElementById('post-content-0'), {
      mode: 'text/x-scss',
      theme: 'mdn-like',
      lineNumbers: true,
      matchBrackets: true,
      gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter', 'CodeMirror-lint-markers'],
      // addons
      styleActiveLine: true,
      styleSelectedText: true,
      continueComments: true,
      foldGutter: true,
      foldOptions: true,
      showTrailingSpace: true,
      autoCloseBrackets: true,
      highlightSelectionMatches: true,
      extraKeys: {
        'Ctrl-Alt-L': fns.autoIndent,
        'Cmd-Alt-L': fns.autoIndent,
        'Ctrl-/': fns.commentBlock,
        'Cmd-/': fns.commentBlock,
        'Ctrl-Alt-/': fns.uncommentBlock,
        'Cmd-Alt-/': fns.uncommentBlock,
        'Tab': fns.tabsToSpaces,
        'Shift-Tab': CodeMirror.commands.indentLess,
        'Cmd-[': CodeMirror.commands.indentLess,
      },
      tabSize: 4,
      indentUnit: 4,
      lint: {
        options: {
          // @see https://github.com/blackmiaool/sass-lint
          rules: {
            'no-empty-rulesets': true,
            'bem-depth': 1,
            'border-zero': 0,
            'brace-style': '1tbs',
            'class-name-format': {
              'allow-leading-underscore': false,
              'convention': '^(?!post\\-[0-9]+)([a-z][a-z0-9]*)(\\-\\-?[a-z0-9]+)*$',
              'convention-explanation': 'Disallowed: leading numbers, uppercase letters, underscores'
            },
            'declarations-before-nesting': true,
            'empty-line-between-blocks': {
              'include': true,
              'allow-single-line-rulesets': false
            },
            'extends-before-declarations': true,
            'extends-before-mixins': true,
            'hex-length': [2, {
              'style': 'short',
            }],
            'hex-notation': [2, {
              'style': 'lowercase',
            }],
            'indentation': [2, {
              'size': 4
            }],
            'mixin-name-format': {
              'allow-leading-underscore': true,
              'convention': 'hyphenatedlowercase'
            },
            'mixins-before-declarations': true,
            'no-disallowed-properties': [],
            'no-duplicate-properties': true,
            'no-ids': [1, true],
            'no-important': true,
            'no-invalid-hex': true,
            'no-mergeable-selectors': true,
            'no-misspelled-properties': true,
            'no-trailing-whitespace': true,
            'no-trailing-zero': true,
            'no-universal-selectors': true,
            'no-url-domains': true,
            'no-vendor-prefixes': true,
            'one-declaration-per-line': true,
            'placeholder-in-extend': true,
            'placeholder-name-format': true,
            'pseudo-element': false,
            'property-sort-order': [0, {
              'ignore-custom-properties': true,
              'order': []
            }],
            'quotes': [2, {
              'style': 'double'
            }],
            'single-line-per-selector': true,
            'space-after-bang': {
              'include': false
            },
            'space-after-colon': {
              'include': true
            },
            'space-after-comma': {
              'include': true
            },
            'space-around-operator': {
              'include': true
            },
            'space-before-bang': {
              'include': true,
            },
            'space-before-brace': {
              'include': true
            },
            'space-before-colon': {
              'include': false
            },
            'space-between-parens': {
              'include': false
            },
            'trailing-semicolon': {
              'inlcude': true
            },
            'url-quotes': true,
            'variable-for-property': false,
            'variable-name-format': {
              'allow-leading-underscore': true,
              'convention': 'hyphenatedlowercase'
            },
            'zero-unit': {
              'include': false
            }
          }
        }
      }
    });

    cm.setSize(null, '80vh');
  };

  setupEvents = function () {

    //signal cm ready:
    var eventcmup = new Event('CodeMirrorRunning');
    window.dispatchEvent(eventcmup);
    //signal Saving:
    var event = new Event('WPsaving');
    wp.data.subscribe(function () {
      var isSavingPost = wp.data.select('core/editor').isSavingPost();
      var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
      if (isSavingPost && !isAutosavingPost) {
        window.dispatchEvent(event);

      }
    })

    wp.data.subscribe(function () {
      var hasSaved = wp.data.select("core/editor").didPostSaveRequestSucceed()
      if (hasSaved) {
        var event2 = new Event('WPSavedSuccessfull');
        window.dispatchEvent(event2);

      }
    })
  }

  /**
   *
   */
  registerEvents = function () {

    cm.on('keyup', fns.autoComplete);

    $('.custom-css input[type="button"]').on('click', triggerFunction);

    //sync codemirror changes to central gutenberg post-data object (gutenberg syncs blocks and textarea by itself):
    window.editor.on('change', function () {
      wp.data.dispatch('core/editor').editPost({content: window.editor.getValue()})
    });

    window.addEventListener('WPSavedSuccessfull', function () {
      // necesseary to prevent losing leading whitespace when saving unedited post:
      wp.data.dispatch('core/editor').editPost({content: window.editor.getValue()});
      // window.editor.setValue(cm.getValue());
    })

  };

  /**
   *
   */
  triggerFunction = function () {
    let functionName = $(this).attr('name'),
      fnName = fns[functionName];

    if (typeof fnName === "function") {
      fnName();
    } else {
      cm.execCommand(functionName);
    }
  };

  /**
   *
   */
  fns.autoIndent = function () {
    // TODO: Auto remove trailing whitespace using the edit/trailingspace.js addon

    cm.eachLine(function (line) {
      cm.indentLine(line.lineNo());
    });
  };

  /**
   *
   */
  fns.autoComplete = function () {
    let e = window.event;
    if (e.keyCode < 65 || e.keyCode > 90 || e.ctrlKey) {
      return;
    }

    cm.execCommand('autocomplete');
  };

  /**
   *
   */
  fns.commentBlock = function () {
    cm.blockComment(
      cm.getCursor(true),
      cm.getCursor(false),
      {fullLines: true}
    );
  };

  /**
   *
   */
  fns.uncommentBlock = function () {
    cm.uncomment(
      cm.getCursor(true),
      cm.getCursor(false),
      {lineComment: true}
    );
  };

  /**
   *
   */
  fns.tabsToSpaces = function () {
    let spaces = Array(cm.getOption('indentUnit') + 1).join(' ');

    cm.replaceSelection(spaces);
  };

  init();

})(jQuery);

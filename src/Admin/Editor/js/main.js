(function ($) {
  'use strict';

  /**
   *
   */
  function init() {

    window.editor = CodeMirror.fromTextArea(document.getElementById('content'), {
      mode: 'text/x-scss',
      theme: 'mdn-like',
      lineNumbers: true,
      matchBrackets: true,
      gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter', 'CodeMirror-lint-markers'],
      // addons
        // selectionPointer: 'pointer',
        // styleSelectedText: true,
      styleActiveLine: true,
      styleSelectedText: true,
      continueComments: true,
      foldGutter: true,
      foldOptions: true,
      showTrailingSpace: true,
      autoCloseBrackets: true,
      highlightSelectionMatches: true,

      extraKeys: {
        'Tab': tabsToSpaces,
        'Shift-Tab': 'indentLess',
        'Cmd-[': 'indentLess',
        'Ctrl-Alt-L': autoIndent,
        'Cmd-Alt-L': autoIndent,
        'Ctrl-/': blockComment,
        'Cmd-/': blockComment,
        'Ctrl-Alt-/': uncomment,
        'Cmd-Alt-/': uncomment,
        'Shift-Ctrl-F': 'replace',
        'Cmd-Alt-F': 'replace',
        'Shift-Ctrl-R': 'replaceAll',
        'Shift-Cmd-Alt-F': 'replaceAll',
        'Ctrl-Space': 'autocomplete'
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
            'no-ids': [ 1, true ],
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

    window.editor.setSize( null, '80vh' );

    editor.on('keyup', autoComplete);
  }

  /**
   *
   * @param cm
   */
  function tabsToSpaces(cm) {
    let spaces = Array(cm.getOption('indentUnit') + 1).join(' ');
    cm.replaceSelection(spaces);
  }

  /**
   *
   * @param cm
   */
  function autoIndent(cm) {

    // TODO: Auto remove trailing whitespace using the edit/trailingspace.js addon

    cm.eachLine(function (line) {
      cm.indentLine(line.lineNo());
    })
  }

  /**
   *
   * @param cm
   * @param e
   */
  function autoComplete(cm, e) {
    if (e.keyCode < 65 || e.keyCode > 90 || e.ctrlKey) {
      return;
    }
    editor.execCommand('autocomplete');
  }

  /**
   *
   * @returns {{from: *, to: *}}
   */
  function getSelectedRange() {
    return { from: editor.getCursor( true ), to: editor.getCursor( false ) };
  }

  /**
   *
   */
  function uncomment() {
    var range = getSelectedRange();
    editor.uncomment(range.from, range.to);
  }

  /**
   *
   */
  function blockComment() {
    var range = getSelectedRange();
    editor.blockComment(range.from, range.to, { fullLines: false });
  }

  init();

})(jQuery);
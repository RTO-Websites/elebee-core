(function ($) {

  window.editor = CodeMirror.fromTextArea(document.getElementById('content'), {
    mode: 'text/x-scss',
    // mode: 'text/css',
    theme: 'mdn-like',
    lineNumbers: true,
    styleActiveLine: true,
    selectionPointer: true,
    extraKeys: {
      'Tab': tabsToSpaces,
      'Shift-Tab': 'indentLess',
      'Ctrl-Alt-L': autoIndent,
      'Cmd-Alt-L': autoIndent,
      'Ctrl-/': 'toggleComment',
      'Cmd-/': 'toggleComment',
      // 'Ctrl-Space': 'autocomplete',
    },
    autoCloseBrackets: true,
    continueComments: true,
    matchBrackets: true,
    foldGutter: true,
    gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter', 'CodeMirror-lint-markers'],
    lint: {
      options: {
        // @see https://github.com/blackmiaool/sass-lint
        rules: {
          'no-empty-rulesets': 1,
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
          'hex-length': {
            'style': 'short',
          },
          'hex-notation': {
            'style': 'lowercase',
          },
          'indentation': {
            'size': 2
          },
          // 'leading-zero': {
          //   'include': true
          // },
          'max-file-line-count': {
            'length': 300
          },
          // 'max-line-length': {
          //   'length': 100
          // },
          'mixin-name-format': {
            'allow-leading-underscore': true,
            'convention': 'hyphenatedlowercase'
          },
          'mixins-before-declarations': true,
          'no-disallowed-properties': [],
          'no-duplicate-properties': true,
          // 'no-empty-rulesets': true
          'no-ids': true,
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
          // 'property-sort-order': {
          //   'order': 'concentric'
          // },
          'pseudo-element': true,
          'quotes': {
            'style': 'single'
          },
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

  editor.on('keyup', autoComplete);

  function tabsToSpaces(cm) {
    let spaces = Array(cm.getOption('indentUnit') + 1).join(' ');
    cm.replaceSelection(spaces);
  }

  function autoIndent(cm) {

    // TODO: Auto remove trailing whitespace using the edit/trailingspace.js addon

    cm.eachLine(function (line) {
      cm.indentLine(line.lineNo());
      if (/^\s+}$/.test(line.text)) {
        // TODO: Fix closing brace indentation
      }
    })
  }

  function autoComplete(cm, e) {
    if (e.keyCode < 65 || e.keyCode > 90) {
      return;
    }
    editor.execCommand('autocomplete');
  }

})(jQuery);
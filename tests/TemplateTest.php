<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

declare( strict_types=1 );

use ElebeeCore\Lib\Template;
use PHPUnit\Framework\TestCase;

final class TemplateTest extends TestCase {

    public function renderingProvider() {

        return [
            [
                '\r\n' .
                '<div  >\r\n' .
                '   test\r\n' .
                '</div>',
                TESTEE_PATH . '/Public/partials/element-default.php',
                [
                    'tag' => 'div',
                    'attributes' => '',
                    'content' => 'test',
                ]
            ],
        ];

    }

    /**
     * @dataProvider renderingProvider
     */
    public function testRendering( $expcted, $templatePath, $vars ) {

        $template = new Template( $templatePath, $vars );

        $this->assertEquals( $expcted, $template->getRendered() );

    }

    protected function tearDown() {

        unset( $this->template );

    }

}
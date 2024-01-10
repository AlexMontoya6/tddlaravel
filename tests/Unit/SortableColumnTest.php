<?php

namespace Tests\Unit;

use App\Sortable;
use Tests\TestCase;
use App\Rules\SortableColumn;

class SortableColumnTest extends TestCase
{
    /** @test */
    function validates_sortable_values()
    {
        $rule = new SortableColumn(['last_name', 'email', 'date', 'id']);

        $this->assertTrue($rule->passes('order', 'id'));
        $this->assertTrue($rule->passes('order', 'id-desc'));
        $this->assertTrue($rule->passes('order', 'last_name'));
        $this->assertTrue($rule->passes('order', 'email'));
        $this->assertTrue($rule->passes('order', 'date'));
        $this->assertTrue($rule->passes('order', 'last_name-desc'));
        $this->assertTrue($rule->passes('order', 'email-desc'));

        $this->assertFalse($rule->passes('order', []));
        $this->assertFalse($rule->passes('order', 'last_name-decendent'));
        $this->assertFalse($rule->passes('order', 'asc-name'));
        $this->assertFalse($rule->passes('order', 'name'));
        $this->assertFalse($rule->passes('order', 'email-des'));

    }
    /** @test */
    function gets_the_info_about_the_column_name_and_the_order_direction()
    {
        $this->assertSame(['last_name', 'asc'], Sortable::info('last_name'));
        $this->assertSame(['last_name', 'desc'], Sortable::info('last_name-desc'));
        $this->assertSame(['email', 'asc'], Sortable::info('email'));
        $this->assertSame(['email', 'desc'], Sortable::info('email-desc'));
    }
}

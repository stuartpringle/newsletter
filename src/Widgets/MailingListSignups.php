<?php

namespace StuartPringle\Newsletter\Widgets;

use Statamic\Widgets\Widget;

class MailingListSignups extends Widget
{
    /**
     * The HTML that should be shown in the widget.
     *
     * @return string|\Illuminate\View\View
     */
    public function html()
    {
        return view('newsletter::widgets.mailing_list_signups');
    }
}

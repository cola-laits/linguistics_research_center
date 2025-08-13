<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.show_donation_popup', false);
        $this->migrator->add('site.donation_popup_text', <<<EOF
<h3>40 for Forty is here!</h3>
<p>UT is asking the community to help support critical projects like the LRC.</p>
<p>Please consider making a donation today <a href="https://40for40.utexas.edu/giving-day/46023/department/46180" target="_blank">here</a>.</p>
<p>$5, $10, $20… anything helps.</p>
<p> Thank you.</p>
<p>
    <div id="donate-button">
        <a href="https://40for40.utexas.edu/giving-day/46023/department/46180"><h3>Donate Now</h3></a>
    <p class="hide-for-medium-down"><a href="https://40for40.utexas.edu/giving-day/46023/department/46180">We need your help to preserve &amp; document ancient languages. Participate today.</a></p>
    </div>
</p>
EOF
);
    }
};

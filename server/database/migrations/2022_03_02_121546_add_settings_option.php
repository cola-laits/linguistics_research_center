<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("INSERT INTO settings (`key`, name, description, value, field, active, created_at, updated_at)"
            . " VALUES (?,?,?,?,?,1,now(),now())", [
            "show_donation_popup",
            "Show Donation Popup",
            "Show a 'please donate' popup to first-time visitors",
            "no",
            '{"name":"value","label":"Show popup?","type":"radio","options":{"no":"No","yes":"Yes"}}'
        ]);
        $donation_popup_text = <<<EOF
                <h3>40 for Forty is here!</h3>
                <p>UT is asking the community to help support critical projects like the LRC.</p>
                <p>Please consider making a donation today <a href="https://40for40.utexas.edu/giving-day/19787/department/25244" target="_blank">here</a>.</p>
                <p>$5, $10, $20… anything helps.</p>
                <p> Thank you.</p>
                <p>
                    <div id="donate-button">
                        <a href="https://40for40.utexas.edu/giving-day/19787/department/25244"><h3>Donate Now</h3></a>
                    <p class="hide-for-medium-down"><a href="https://40for40.utexas.edu/giving-day/19787/department/25244">We need your help to preserve &amp; document ancient languages. Participate today.</a></p>
                    </div>
                </p>
EOF;
        DB::insert("INSERT INTO settings (`key`, name, description, value, field, active, created_at, updated_at)"
                   . " VALUES (?,?,?,?,?,1,now(),now())", [
                       "donation_popup_text",
                       "Donation Popup text",
                       "Text/HTML of donation popup",
                       $donation_popup_text,
                       '{"name":"value","label":"Donation popup text","type":"textarea"}'
                   ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM settings where `key`=?", ['show_donation_popup']);
        DB::delete("DELETE FROM settings where `key`=?", ['donation_popup_text']);
    }
}

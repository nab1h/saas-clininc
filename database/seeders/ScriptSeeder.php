<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Script;
use Illuminate\Database\Seeder;

class ScriptSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = Clinic::all();

        if ($clinics->isEmpty()) {
            return;
        }

        foreach ($clinics as $clinic) {
            Script::create([
                'clinic_id' => $clinic->id,
                'type' => 'google_analytics',
                'name' => 'Google Analytics - ' . $clinic->name,
                'code' => '<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXXXXXX-X"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'UA-XXXXXXXXX-X\');
</script>',
                'position' => 'head',
                'order' => 1,
                'is_active' => true,
            ]);

            Script::create([
                'clinic_id' => $clinic->id,
                'type' => 'facebook_pixel',
                'name' => 'Facebook Pixel - ' . $clinic->name,
                'code' => '<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,\'script\',
\'https://connect.facebook.net/en_US/fbevents.js\');
fbq(\'init\', \'XXXXXXXXXXXXXXXX\');
fbq(\'track\', \'PageView\');
</script>
<noscript>
<img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=XXXXXXXXXXXXXXXX&ev=PageView&noscript=1"/>
</noscript>',
                'position' => 'head',
                'order' => 2,
                'is_active' => true,
            ]);
        }
    }
}

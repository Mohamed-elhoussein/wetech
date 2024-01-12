<?php

namespace App\Bots;


       
 


class ClickUp
{

    private $domaine;
    private $project;
    private $clickup_api_url = 'https://api.clickup.com/api/v2/list/175209577/task';
    private $header_authorization = '6650871_5d0caca9ae04e02cd884d4d8c4cea90ba5c8f4ac';
    private $status = 'قائمة المهام';
    private $task_name;
    private $description;



    public function project($project)
    {
        $this->project = $project;
        return $this;
    }

    public function generate_tak_name()
    {
        $this->task_name =   ' FATAL ERROR 500 :  ' .  $this->clear_domain(request()->getHost());
    }

    public function clear_domain($domaine)
    {
        $NewText = rtrim($domaine, '/');
        $NewText = str_replace('www.', '', $NewText,);


        if (strpos($NewText, 'http://') !== false) {
            $NewText = str_replace('http://', '', $NewText);
        }

        if (strpos($NewText, 'https://') !== false) {
            $NewText = str_replace('https://', '', $NewText);
        }

        return $NewText;
    }


    public function details($details = NULL)
    {
        if ($details) {
            $this->description = $details;
        }

        return $this;
    }



    public function post_params()
    {
        return [
            'name'                      => $this->task_name,
            'markdown_description'      => $this->description,
            'tags'                      => ['Error 500'],
            'status'                    => $this->status,
            'due_date_time'             => false,
            'start_date_time'           => false,
            'notify_all'                => true,
            'parent'                    => NULL,
            'links_to'                  => NULL,
            'custom_fields'             => [
                [
                    'id'     => '065002c9-44a8-49c6-93d2-724e95bbea44',
                    'value'  => $this->project
                ]
            ]
        ];
    }

    public function post_headers()
    {
        return [
            'Content-Type: application/json',
            'Authorization: ' . $this->header_authorization,
        ];
    }

    public function send()
    {

        $this->generate_tak_name();
        $this->details();


        $fields  = $this->post_params();
        $headers = $this->post_headers();


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->clickup_api_url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 20);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (isset($fields)) {
            $data = json_encode($fields);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $json_return_data = curl_exec($ch);

        curl_close($ch);

        return $this;
    }
}






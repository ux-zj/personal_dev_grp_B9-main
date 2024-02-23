<?php

/**
 * Purpose of this class is to create methods that access data using the API.
 */

class api
{
    public function getTournaments(){
        $url = "http://localhost:804/src/Api/Tournament/read.php";
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1'
            )
        );
        $context = stream_context_create($opts);
        $stream = fopen($url, 'r', false, $context);
        $content = stream_get_contents($stream);
        fclose($stream);

        return json_decode($content);
    }

    public function getTournamentById($id){
        $url = "http://localhost:804/src/Api/Tournament/read_id.php?t_id=" . $id;
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1'
            )
        );
        $context = stream_context_create($opts);
        $stream = fopen($url, 'r', false, $context);
        $content = stream_get_contents($stream);
        fclose($stream);
        return json_decode($content);
    }

    public function getUsername($id){
        $url = "http://localhost:804/src/Api/user/read_id.php?u_id=".$id;
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1'
            )
        );
        $context = stream_context_create($opts);
        $stream = fopen($url, 'r', false, $context);
        $content = stream_get_contents($stream);
        fclose($stream);
        $user = json_decode($content);

        return $user->username;
    }

    public function getUser($username, $password): bool
    {
        $url = "http://localhost:804/src/Api/user/read_php";
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1'
            )
        );
        $context = stream_context_create($opts);
        $stream = fopen($url, 'r', false, $context);
        $content = stream_get_contents($stream);
        fclose($stream);
        $users = json_decode($content);
        $match_found = false;
        foreach($users as $user){
            if ($user->username === $username && $user->password === $password) {
                $match_found = true;
            } else {
                $match_found = false;
            }
        }
        return $match_found;
    }

    public function createTournament($tournament) : int
    {
        $postdata = json_encode($tournament);
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context = stream_context_create($opts);
        //var_dump($result);
        return file_get_contents('http://localhost:804/src/Api/tournament/create.php', false, $context);
    }

    public function getStagesForTournament($id){
        $url = "http://localhost:804/src/Api/Tournament/Stage/read_id.php?t_id=" . $id;
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1'
            )
        );
        $context = stream_context_create($opts);
        $stream = fopen($url, 'r', false, $context);
        $content = stream_get_contents($stream);
        fclose($stream);

        return json_decode($content);
    }

    public function getActivityById($id){
        $url = "http://localhost:804/src/Api/activity/read_id.php?act_id=" . $id;
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1'
            )
        );
        $context = stream_context_create($opts);
        $stream = fopen($url, 'r', false, $context);
        $content = stream_get_contents($stream);
        fclose($stream);

        return json_decode($content);
    }

    public function getStageByID($id)
    {
        $url = "http://localhost:804/src/Api/tournament/stage/read_id.php?s_id=" . $id;
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'max_redirects' => '0',
                'ignore_errors' => '1'
            )
        );
        $context = stream_context_create($opts);
        $stream = fopen($url, 'r', false, $context);
        $content = stream_get_contents($stream);
        fclose($stream);

        return json_decode($content);
    }

}
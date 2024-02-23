<?php
/*
 *
 */
enum Format : string {

    const __default = self::Gauntlet;

    case FFA = "FFA";
    case Gauntlet = "Gauntlet";
    case RoundRobin = "Round Robin";

    public function generateFromFormat($teams): array
    {
        return match($this) {
            Format::FFA => $this->createFreeForAll($teams),
            Format::Gauntlet => $this->createGauntlet($teams),
            Format::RoundRobin => $this->createRoundRobin($teams)
        };
    }

    // participants with -1 are knocked out, so don't include them.
    public function removeKnockedOut($teams): array
    {
        $teams = json_decode($teams, true);

        // Sort by points
        $points = array_column($teams, 'points');
        array_multisort($points, SORT_DESC, $teams);

        $qualified_teams = [];
        for($i = 0; $i <= (sizeof($teams)-1); $i++){
            if($teams[$i]["points"] > -1) {
                $qualified_teams[$i] = array(
                    "team" => $teams[$i]["team"],
                    "seed" => $i+1
                );
            }
        }
        return $qualified_teams;
    }

    public function createFreeForAll($teams): array
    {
        $match = array();
        $match[] = new Matchup($teams);
        return $match;
    }

    public function createGauntlet($teams): array
    {
        //print_r($teams);
        $match = array();
        $removed = array();

        if(sizeof($teams) <= 1){
            $match[] = new Matchup(new Team(-1, "Pending", array(new Participant(-1))));
            return $match;
        }

        // Remove last seed if they do not have a full team.
        $size = sizeof($teams);
        foreach($teams[$size-1]->getTeamMembers() as $p){
            if($p->getId() == -1) {
                $removed[] = array_pop($teams);
            }
        }

        // Create first match of the gauntlet using the last seeds.
        $size = sizeof($teams);
        $match[] = new Matchup($teams[$size-1], $teams[$size-2]);

        $removed[] = array_pop($teams);
        $removed[] = array_pop($teams);

        // Generate the rest of the matchups
        $j = 0;
        for($i = sizeof($teams) - 1; $i > 0; $i--){
            if($match[$j]->matchWinner() == null) {
                $challenger = new Team(-1, "Pending", array(new Participant(-1)));
            } else {
                $challenger = $match[$j]->matchWinner();
            }
            //print_r($match[$j]);
            $match[] = new Matchup($teams[$i], $challenger);
            $j++;
        }
        return $match;
    }

    public function createRoundRobin($teams): array
    {
        $match = array();
        $removed = array();

        // Remove last seed if they do not have a full team.
        $size = sizeof($teams);
        foreach($teams[$size-1]->getTeamMembers() as $p){
            if($p->getId() == -1) {
                $removed[] = array_pop($teams);
            }
        }

        // Create first match of the gauntlet using the last seeds.
        $size = sizeof($teams);
        $match[] = new Matchup($teams[$size-1], $teams[$size-2]);

        $removed[] = array_pop($teams);
        $removed[] = array_pop($teams);

        // Generate the rest of the matchups
        $j = 0;
        for($i = sizeof($teams) - 1; $i > 0; $i--){
            if($match[$j]->matchWinner() == null) {
                $challenger = new Team(-1, "Pending", array(new Participant(-1)));
            } else {
                $challenger = $match[$j]->matchWinner();
            }
            //print_r($match[$j]);
            $match[] = new Matchup($teams[$i], $challenger);
            $j++;
        }
        return $match;

//        $total_teams = sizeof(json_decode($teams));
//        $match = [];
//        $m_id = 0;
//        // If total teams (n) is even, then each round has (n/2)*(n-1) games with (n-1) rounds.
//        // If odd, then each round has (n-1)/2 rounds with n rounds. -> add dummy vs highest seed
//
//        if($total_teams % 2 == 0) {
//            $n = $total_teams;
//        } else {
//            $n = $total_teams + 1;
//        }
//
//        echo $total_teams;
//
//        for($i=0; $i < $n; $i++){
//
//            for($j = $n - 1; $j > $i; $j--){
//                $m_id++;
//                $match[] = array (
//                    "m_id" => $m_id,
//                    "player 1" => $i,
//                    "player 2" => $j
//                );
//            }
//        }
//        return $match;

    }

}
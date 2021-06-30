<?php

/**
 * Use this file to output reports required for the SQL Query Design test.
 * An example is provided below. You can use the `asTable` method to pass your query result to,
 * to output it as a styled HTML table.
 */

$database = 'nba2019';
require_once 'vendor/autoload.php';
require_once 'include/utils.php';

/*
 * Example Query
 * -------------
 * Retrieve all team codes & names
 */
echo '<h1>Example Query</h1>';
$teamSql = "SELECT * FROM team";
$teamResult = query($teamSql);
// dd($teamResult);
echo asTable($teamResult);

/*
 * Report 1
 * --------
 * Produce a query that reports on the best 3pt shooters in the database that are older than 30 years old. Only
 * retrieve data for players who have shot 3-pointers at greater accuracy than 35%.
 *
 * Retrieve
 *  - Player name
 *  - Full team name
 *  - Age
 *  - Player number
 *  - Position
 *  - 3-pointers made %
 *  - Number of 3-pointers made
 *
 * Rank the data by the players with the best % accuracy first.
 */
echo '<h1>Report 1 - Best 3pt Shooters</h1>';
$bestThreePointShootersSql = "SELECT
roster.name,
team.name as team_name,
player_totals.age,
roster.number,
roster.pos,
player_totals.3pt,
    CAST((player_totals.3pt / player_totals.3pt_attempted)*100 as DECIMAL(4,2)) as percentage
From
player_totals
JOIN roster on
roster.id =player_totals.player_id
JOIN team on
team.code = roster.team_code
ORDER BY player_totals.3pt DESC
LIMIT 3
";
$bestThreePointShooters = query($bestThreePointShootersSql);
echo asTable($bestThreePointShooters);

/*
 * Report 2
 * --------
 * Produce a query that reports on the best 3pt shooting teams. Retrieve all teams in the database and list:
 *  - Team name
 *  - 3-pointer accuracy (as 2 decimal place percentage - e.g. 33.53%) for the team as a whole,
 *  - Total 3-pointers made by the team
 *  - # of contributing players - players that scored at least 1 x 3-pointer
 *  - of attempting player - players that attempted at least 1 x 3-point shot
 *  - total # of 3-point attempts made by players who failed to make a single 3-point shot.
 *
 * You should be able to retrieve all data in a single query, without subqueries.
 * Put the most accurate 3pt teams first.
 */
echo '<h1>Report 2 - Best 3pt Shooting Teams</h1>';
// write your query here
$bestThreePointShootingTeamsSql = "SELECT team.name, SUM(player_totals.3pt) as total_3pt_made_by_team, COUNT(case when player_totals.3pt > 1 then 1 end) as no_of_contributing_players, count(case when player_totals.3pt_attempted > 1 then 1 end) as no_of_attempting_players, CAST(SUM(player_totals.3pt) / SUM(player_totals.3pt_attempted )*100 as DECIMAL(4,2)) as accuracy, COUNT(case when player_totals.3pt = 0 then 1 end) as players_failed_to_make_3_point_shot FROM player_totals JOIN roster ON roster.id = player_totals.player_id JOIN team on roster.team_code = team.code WHERE player_totals.3pt > 0 or player_totals.3pt_attempted > 0 GROUP BY team.name";
// echo ($bestThreePointShootingTeamsSql);
$bestThreePointShootingTeams = query($bestThreePointShootingTeamsSql);
echo asTable($bestThreePointShootingTeams);
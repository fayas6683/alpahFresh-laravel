<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 12/9/15
 * Time: 9:40 AM
 */


class VisitorController extends BaseController

{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

    }

    public function visitorsToPages()
    {
        try
        {
            $start              = Input::get('start');
            $end                = Input::get('end');
            $customTemplateId   = Input::get('custom_template_id');
            $dateUnit           = Input::get('date_unit');

            if(empty($start))
                $start        = date('Y-m-01');

            if(empty($end))
                $end          = date("Y-m-d");

            $queryString = '';

           switch($dateUnit){

               case 'month' : $queryString = 'DATE_FORMAT(n.created_at, \'%m-%Y\')';

                break;

               case 'day'   : $queryString = 'DATE_FORMAT(n.created_at, \'%d-%m-%Y\')';

                break;

               case 'year'  : $queryString = 'DATE_FORMAT(n.created_at, \'%Y\')';
           }

            $sqlQuery = "SELECT dated, IFNULL(n.custom_templates_id,".$customTemplateId."), IFNULL(n.amount,0) AS firstPage, IFNULL(r.Amount,0) AS nextPage , ".$queryString." as date  FROM
                                                        (SELECT a.dated
                                                FROM (
                                                SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS dated
                                                FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
                                                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
                                                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
                                                ) a
                                                WHERE a.dated BETWEEN '".$start."' AND '".$end."') x

                                                LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id, c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id , v.created_at) n

                                                            ON x.dated = n.created_at



                                                     LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id  , c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id, v.created_at
                                                         ) r ON n.next_page = r.custom_templates_id

                                                  WHERE n.custom_templates_id IS NULL OR n.custom_templates_id= ".$customTemplateId."
                                                  GROUP BY IFNULL(n.custom_templates_id,".$customTemplateId."), dated, r.amount, n.amount , ".$queryString."
                                                        ";


            $visitorsByMonth = DB::select($sqlQuery);

            $arrayStats = array();

            foreach( $visitorsByMonth as $page )
            {
                array_push($arrayStats,array(strtotime($page->dated),$page->firstPage,$page->nextPage));
            }


            return Response::json(array('status' => 'success' , 'data' => $arrayStats ),200);

        }catch (Exception $ex )
        {
            return Response::json(array('status' => 'error' , 'data' => $ex->getMessage() ));
        }
    }

    public function visitorsToPagesToday()
    {
        try
        {
            $start              = Input::get('start');
            $end                = Input::get('end');
            $customTemplateId   = Input::get('custom_template_id');
            $dateUnit           = Input::get('date_unit');

            if(empty($start))
                $start        = date('Y-m-01');

            if(empty($end))
                $end          = date("Y-m-d");

            $queryString = '';

            switch($dateUnit){

                case 'month' : $queryString = 'DATE_FORMAT(n.created_at, \'%m-%Y\')';

                    break;

                case 'day'   : $queryString = 'DATE_FORMAT(n.created_at, \'%d-%m-%Y\')';

                    break;

                case 'year'  : $queryString = 'DATE_FORMAT(n.created_at, \'%Y\')';
            }

            $sqlQuery = "SELECT dated, IFNULL(n.custom_templates_id,".$customTemplateId."), IFNULL(n.amount,0) AS firstPage, IFNULL(r.Amount,0) AS nextPage , ".$queryString." as date  FROM
                                                        (SELECT a.dated
                                                FROM (
                                                SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS dated
                                                FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
                                                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
                                                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
                                                ) a
                                                WHERE a.dated BETWEEN '".$start."' AND '".$end."') x

                                                LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id, c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id , v.created_at) n

                                                            ON x.dated = n.created_at



                                                     LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id  , c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id, v.created_at
                                                         ) r ON n.next_page = r.custom_templates_id

                                                  WHERE ( n.custom_templates_id IS NULL OR n.custom_templates_id= ".$customTemplateId.")  AND dated = CURDATE()
                                                  GROUP BY IFNULL(n.custom_templates_id,".$customTemplateId."), dated, r.amount, n.amount , ".$queryString."
                                                        ";


            $visitorsByMonth = DB::select($sqlQuery);

            $arrayStats = array();

            foreach( $visitorsByMonth as $page )
            {
                array_push($arrayStats,array(strtotime($page->dated),$page->firstPage,$page->nextPage));
            }


            return Response::json(array('status' => 'success' , 'data' => $arrayStats ),200);

        }catch (Exception $ex )
        {
            return Response::json(array('status' => 'error' , 'data' => $ex->getMessage() ));
        }
    }

    public function visitorsToPagesYesterday()
    {
        try
        {
            $start              = Input::get('start');
            $end                = Input::get('end');
            $customTemplateId   = Input::get('custom_template_id');
            $dateUnit           = Input::get('date_unit');

            if(empty($start))
                $start        = date('Y-m-01');

            if(empty($end))
                $end          = date("Y-m-d");

            $queryString = '';

            switch($dateUnit){

                case 'month' : $queryString = 'DATE_FORMAT(n.created_at, \'%m-%Y\')';

                    break;

                case 'day'   : $queryString = 'DATE_FORMAT(n.created_at, \'%d-%m-%Y\')';

                    break;

                case 'year'  : $queryString = 'DATE_FORMAT(n.created_at, \'%Y\')';
            }

            $sqlQuery = "SELECT dated, IFNULL(n.custom_templates_id,".$customTemplateId."), IFNULL(n.amount,0) AS firstPage, IFNULL(r.Amount,0) AS nextPage , ".$queryString." as date  FROM
                                                        (SELECT a.dated
                                                FROM (
                                                SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS dated
                                                FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
                                                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
                                                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
                                                ) a
                                                WHERE a.dated BETWEEN '".$start."' AND '".$end."') x

                                                LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id, c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id , v.created_at) n

                                                            ON x.dated = n.created_at



                                                     LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id  , c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id, v.created_at
                                                         ) r ON n.next_page = r.custom_templates_id

                                                  WHERE ( n.custom_templates_id IS NULL OR n.custom_templates_id= ".$customTemplateId.")  AND dated = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
                                                  GROUP BY IFNULL(n.custom_templates_id,".$customTemplateId."), dated, r.amount, n.amount , ".$queryString."
                                                        ";


            $visitorsByMonth = DB::select($sqlQuery);

            $arrayStats = array();

            foreach( $visitorsByMonth as $page )
            {
                array_push($arrayStats,array(strtotime($page->dated),$page->firstPage,$page->nextPage));
            }


            return Response::json(array('status' => 'success' , 'data' => $arrayStats ),200);

        }catch (Exception $ex )
        {
            return Response::json(array('status' => 'error' , 'data' => $ex->getMessage() ));
        }
    }

    public function visitorsToPagesInPercentage()
    {
        try
        {
            $start              = Input::get('start');
            $end                = Input::get('end');
            $customTemplateId   = Input::get('custom_template_id');
            $dateUnit           = Input::get('date_unit');

            if(empty($start))
                $start        = date('Y-m-01');

            if(empty($end))
                $end          = date("Y-m-d");

            $queryString = '';

            switch($dateUnit){

                case 'month' : $queryString = 'DATE_FORMAT(n.created_at, \'%m-%Y\')';

                    break;

                case 'day'   : $queryString = 'DATE_FORMAT(n.created_at, \'%d-%m-%Y\')';

                    break;

                case 'year'  : $queryString = 'DATE_FORMAT(n.created_at, \'%Y\')';
            }


            $sqlQuery = "SELECT dated, IFNULL(n.custom_templates_id,".$customTemplateId."), 100.0*IFNULL(1.0*r.amount/NULLIF(n.amount,0),0) AS conversionRate , ".$queryString." as date  FROM
                                                        (SELECT a.dated
                                                FROM (
                                                SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS dated
                                                FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
                                                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
                                               CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
                                                ) a
                                                WHERE a.dated BETWEEN '".$start."' AND '".$end."') x

                                                LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id, c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id , v.created_at) n

                                                            ON x.dated = n.created_at



                                                     LEFT OUTER JOIN

                                                        (SELECT v.custom_templates_id  , c.next_page, COUNT(DISTINCT(v.ip_address)) AS amount , v.created_at
                                                            FROM visitor v
                                                            LEFT JOIN custom_templates c
                                                            ON c.id = v.custom_templates_id
                                                            GROUP BY v.custom_templates_id, v.created_at
                                                         ) r ON n.next_page = r.custom_templates_id

                                                  WHERE n.custom_templates_id IS NULL OR n.custom_templates_id= ".$customTemplateId."
                                                  GROUP BY IFNULL(n.custom_templates_id,".$customTemplateId."), dated, r.amount, n.amount , ".$queryString."
                                                        ";

            $visitorsByMonth = DB::select($sqlQuery);

            $arrayStats = array();

            foreach( $visitorsByMonth as $page )
            {
                array_push($arrayStats,array(strtotime($page->dated),$page->conversionRate));
            }


            return Response::json(array('status' => 'success' , 'data' => $arrayStats ),200);

        }catch (Exception $ex )
        {
            return Response::json(array('status' => 'error' , 'data' => $ex->getMessage() ));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

    }

}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


use App\Entity\CSV;
use App\Repository\CSVRepository;


class AjaxController extends AbstractController
{

    public function fetchData(): Response
    {
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; 
        $columnIndex = $_POST['order'][0]['column']; 
        $columnName = $_POST['columns'][$columnIndex]['data']; 
        $columnSortOrder = $_POST['order'][0]['dir']; 
        $searchValue = @$_POST['search']['value'];
        $totalRecords = $this->getDoctrine()->getRepository(CSV::class)->getTotalCount();
        if(@$totalRecords[0]['count'])
            $totalRecords=$totalRecords[0]['count'];
        else 
            $totalRecords=0;
        if($draw>1){
            $csvdata = $this->getDoctrine()->getRepository(CSV::class)->findOneBySomeField($searchValue,$columnName,$columnSortOrder,$row,$rowperpage);
            $totalRecordwithFilter=$this->getDoctrine()->getRepository(CSV::class)->findOneBySomeFieldCount($searchValue,$columnName,$columnSortOrder,$row,$rowperpage);
            if(is_numeric(@$totalRecordwithFilter[0]['count']))
                $totalRecordwithFilter=$totalRecordwithFilter[0]['count'];
            else
                $totalRecordwithFilter=0;
        }
        else{
            $csvdata = $this->getDoctrine()->getRepository(CSV::class)->getData();
            $totalRecordwithFilter=$totalRecords;
        }

        $finaldata=array('data'=>[]);
        // if(!is_array($csvdata))
            // $csvdata=array();
        foreach ($csvdata as $key => $value) {
            $tempdata['id']=$value->getId();
            $tempdata['Name']=$value->getName();
            $tempdata['dob']=$value->getDob()->format('d M y');
            $to   = new \DateTime('today');
            $tempdata['age']=$value->getDob()->diff($to)->y;
            $tempdata['ReportingManager']=$value->getReportingManager();
            $tempdata['Salary']=$value->getSalary();
            $tempdata['Department']=$value->getDepartment();
            array_push($finaldata['data'],$tempdata);
        }
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $finaldata['data']
        );
        return new JsonResponse($response);
    }

    public function importData()
    {
        $response=array('status'=>0);
        if(!empty($_FILES['csv_file']['tmp_name']))
        {
            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            fgetcsv($file_data);
            $to   = new \DateTime('today');
            while($row = fgetcsv($file_data))
            {
                $data=array();
                $data['name']=$row[0];
                $data['dob']= new \DateTime($row[1]);
                $data['age']= $data['dob']->diff($to)->y;
                $data['ReportingManager']=$row[2];
                $data['Salary']=$row[3];
                $data['Department']=$row[4];
                $datainsert=$this->getDoctrine()->getRepository(CSV::class)->addInCSV($data);
            }
            $response=array('status'=>1);
        }
        return new JsonResponse($response);
    }
    public function resetData(){
        $response=array('status'=>1);
        $dataremove=$this->getDoctrine()->getRepository(CSV::class)->deleteAllData();
        return new JsonResponse($response);
    }
}

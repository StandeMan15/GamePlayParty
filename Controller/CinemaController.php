<?php
require_once './Models/Cinema.php';
require_once './Models/Lounge.php';
require_once './Classes/Display.php';

class CinemaController
{
    public function __construct()
    {
        $this->Cinema = new Cinema();
        $this->Lounge = new Lounge();
        $this->Display = new Display();
    }

    public function __destruct()
    {
    }

    public function handleRequest()
    {
        try {

            $action = isset($_GET['op']) ? $_GET['op'] : 'index';
            $cinema_id = isset($_GET['id']) ? $_GET['id'] : '';


            switch ($action) {
                case 'details':
                    $this->detailCinema($cinema_id);
                    break;
                case 'create':
                    $this->collectCreate();
                    break;
                case 'read':
                    $this->read($cinema_id);
                    break;
                case 'update':
                    $this->update();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'index':
                    $this->allCinema();
                    break;
                case 'readAll':
                    $this->readAll();
                    break;
                default:
                    http_response_code(404);
                    break;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function detailCinema($cinema_id)
    {
        $result = $this->Lounge->timeSlots($cinema_id);
        $button = $this->Display->createTimeslotButtons($result);

        $result = $this->Cinema->read($cinema_id);
        $informationText = $this->Display->convertToText($result);
        include 'Views/Pages/cinemaDetails.php';
    }

    public function allCinema()
    {
        $result = $this->Cinema->all();
        $list = $this->Display->createCinemaList($result);
        include 'Views/Pages/searchCinemas.php';
    }
    public function collectCreate()
    {
        $name = isset($_REQUEST['cinema_name']) ? $_REQUEST['cinema_name'] : null;
        $desc = isset($_REQUEST['cinema_desc']) ? $_REQUEST['cinema_desc'] : null;
        $reachability = isset($_REQUEST['cinema_reachability']) ? $_REQUEST['cinema_reachability'] : null;

        if (isset($_REQUEST['submit'])) {
            $html = $this->Cinema->create($name, $desc, $reachability);
        }

        include 'Views/Pages/Admin/Cinema/createCinema.php';
    }
    public function read($cinema_id)
    {
        $result = $this->Cinema->read($cinema_id);
        $res = $this->Display->createTable($result);

        include 'Views/Pages/Admin/cms.php';
    }
    public function update()
    {

    }
    public function delete()
    {

    }
    public function readAll()
    {
        $result = $this->Cinema->showAll();
        $res = $this->Display->createTable($result,true, true);
        include 'Views/Pages/Admin/cms.php';
    }
}

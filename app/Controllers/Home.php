<?php

namespace App\Controllers;

use App\Entities\Menu;
use App\Models\MenuModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\BaseConnection;
use GuzzleHttp\Exception\GuzzleException;

/**
 * @property BaseConnection $db
 */
class Home extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->db = db_connect();
    }


    public function index()
    {
        return view('welcome_message');
    }
    public function fetch()
    {

        $menuRecords = $this->db->table('menus')
            ->where('parent_id', null)
            ->get()
            ->getResult();

        $result = [];

        foreach ($menuRecords as $data) {
            $singleData = [
                "id" => $data->id,
                "label" => $data->label,
                "children" => $this->populateChildren($data)
            ];

            $result[] = $singleData;
        }


        sort($result);
        return $this->response->setJSON($result);
    }
    public function insert()
    {
        try {
            $response = json_decode(file_get_contents('https://dev.shepherd.appoly.io/fruit.json'));

            if ($response) {
                $db = db_connect();
                
                $db->table('menus')->truncate();

                foreach ($response->menu_items as $menu_item) {
                    $this->createMenuData($menu_item);
                }

                echo "Records inserted Successfully";
            }


        } catch (GuzzleException $e) {
            return "Network Error occurred";
        }


//        return view('welcome_message');
    }

    private function createMenuData($menuItem, $parent = null) {
        $menuModel = new MenuModel();

        $menu = new Menu();

        $data = [
            "label" => $menuItem->label,
            "parent_id" => $parent
        ];

        $menu->fill($data);

        $menuModel->insert($menu);

        if (!empty($menuItem->children)) {
            foreach ($menuItem->children as $childMenu) {
                $this->createMenuData($childMenu, $menuModel->getInsertID());
            }
        }
}

    private function populateChildren($record): array
    {
        $childrenRecords = $this->db->table('menus')
            ->where('parent_id', $record->id)
            ->get()
            ->getResult();

        $result = [];

        foreach ($childrenRecords as $data) {
            $singleData = [
                "id" => $data->id,
                "label" => $data->label,
                "children" => $this->populateChildren($data)
            ];

           $result[] = $singleData;
        }

        sort($result);

        return $result;
    }


    public function update($id = 0, $name = "null")
    {
//        if ($this->validate([
//            "id" => "required",
//            "name" => "required"
//        ])) {
    


            $model = new MenuModel();

            $menu = $model->find($id);
            $menu['label'] = $name;

            

            $model->save($menu);

            return $this->response->setJSON(["Updated data"]);
//        }
        // return redirect()->back();
    }
}

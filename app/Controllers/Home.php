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
        $data = $this->fetch();
        return view(    'welcome_message', ["data"=>$data]);
    }

    public function fetch()
    {

        $menuRecords = $this->db->table('menus')
            ->where('parent_id', null)
            ->orderBy('label', 'ASC')
            ->get()
            ->getResult();

        $result = [];

        foreach ($menuRecords as $data) {
            $singleData = [
                "id" => $data->id,
                "label" => $data->label,
                "children" =>  $this->populateChildren($data)
            ];


            $result[] = $singleData;
        }
        return $result;
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

    private function createMenuData($menuItem, $parent = null)
    {
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
            ->orderBy('label', 'ASC')
            ->get()
            ->getResult();

        $result = [];

        foreach ($childrenRecords as $data) {
            $singleData = [
                "id" => $data->id,
                "label" => $data->label,
                "children" => $this->populateChildren($data)
            ];

//            asort($singleData);

            $result[] = $singleData;
        }

//        ksort($result);

        return $result;
    }


    public function update($id = 0, $name = "null")
    {

        $model = new MenuModel();

        $menu = $model->find($id);
        $menu['label'] = $name;


        $model->save($menu);

        return $this->response->setJSON(["Updated data"]);
//        }
        // return redirect()->back();
    }

    public function renderList($menu): string
    {
        $list = "<ul>";
        foreach ($menu as $child) {
                $list .= "<li><p>child</p><li>";
                if (!empty($child['children'])) {
                    $list .= "<ul>";
                    foreach ($child['children'] as $sublist) {
                        $this->renderList($sublist);
                    }
                    $list .= "</ul>";
                }

        }
        $list .= "</ul>";

        return $list;
    }

}
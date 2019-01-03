<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\UsersController;
use App\Controller\IllustsController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use RuntimeException;

class IllustPostsController extends AppController
{
    
    //共通処理
    public function initialize()
    {
        parent::initialize();
        $url = Router::url('/', true); 
        $this->set(compact('url'));
    }

    //イラスト新規追加ページ
    public function illustAdd()
    {
        $user = $this->Auth->user('id');
        $this->set('user_id', $user);
    }

    //イラスト情報を更新する処理
    public function illustsSave()
    {
    $illusts = TableRegistry::getTableLocator()->get('illusts');
    $post = $illusts->newEntity();
        if($this->request->is('post')){
            $this->illustUpload();
            $post = $illusts->patchEntity($post,$this->request->getData());
            $illusts->save($post); 
            return $this->redirect('/Users/mypage');
        }
    }

    //画像アップロード処理
    function illustUpload(){
        //URLに複合主キーを入れる
        $urlGenerate =$this->request->getData('user_id').'_'.$count->contentCounter($user_id).'_';
        $fileName = $this->request->getData('file');
        //ファイルアップロード
        $count = new IllustsController();
        move_uploaded_file( //TODO: ここの処理をFTPあたりに変換して別途ストレージサーバを立てそこに保管する
            $fileName['tmp_name'], 
            WWW_ROOT.'/img/illust_img/'.$urlGenerate.$fileName['name']
        );
        
        //DB保存用のURL
        $imgUrl = 'img/illust_img/'.$urlGenerate.$fileName['name'];
        $this->request = $this->request->withData('illust_url', $imgUrl);
        return $this->request;
    }
}

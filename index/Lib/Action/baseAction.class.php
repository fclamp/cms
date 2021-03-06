<?php
/**
 * 
 * 前台基本
 * @author fc_lamp
 *
 */
class baseAction extends Action
{
	
	
	protected  $article_mode = NULL;
	protected $article_data_mode = NULL;	
	
	public $cate_type = array('1'=>'列表栏目','2'=>'单页栏目');
	
	public $default_img;	
	
	public $list_url = NULL;//列表页URL
	public $show_url = NULL;//详情URL
	
	public function _initialize()
	{		
		include ROOT_PATH . '/includes/lib_common.php';
		
		$this->default_img  = ROOT_URL.'/statics/scxxwh/images/default.png';
		
		$this->article_mode = D ( 'article' );
		$this->article_data_mode = D ( 'article_data' );
		
		$this->list_url = '/index/lists/cateid/';
		$this->show_url = '/index/showPage/id/';
		
		$this->assign('list_url',$this->list_url);
		$this->assign('show_url',$this->show_url);
		
		
		//获取导航连接(顶部、底部、友情)
		$this->assign('ACTION_NAME',ACTION_NAME);
		$this->assign('MODULE_NAME',MODULE_NAME);
		
		$this->assign('ALLLinks',get_avalinks('id,catid,name,url,exten_catid'));
		
		
		//获取网站基设置
		$setting_mod = 	D ( 'setting' );
		$setting = $setting_mod->select ();
		$SITEINFO = array();
		foreach ( $setting as $val )
		{
			
			if($val['name']=='site_bottominfo' or $val['name']=='site_rightinfo')
			{
				$tpl = explode("\n",$val['data']);
				$s = '';
				$n = count($tpl);
				foreach ($tpl as $k=>$vs)
				{
					$vs = htmlspecialchars($vs);
					$c_n = substr_count($vs,' ');
					$vs = preg_replace('/\s/','&nbsp;',$vs,-1,$c_n);					
					if($val['name']=='site_bottominfo' and $n==$k+1)
					{			
						$s .= '<p>'.$vs.'</p>';//.' 网站构建：<a href="http://team.chengdu.cn">成都全搜索技术团队</a><p>';
					}else
					{
						$s .= '<p>'.$vs.'</p>';
					}
				}
				$SITEINFO[$val['name']] = $s;
			}else
			{
				$SITEINFO[$val['name']] = htmlspecialchars($val['data']);	
			}
		}
		$this->assign('SITEINFO',$SITEINFO);
	}
	
	/**
	 * 
	 * 404
	 */
	public function error_404()
	{
		send_http_status ( 404 );
		$this->display ( 'public:404' );
		exit ();
	}

	//返回分页信息
	public function pager($count, $pagesize = 20)
	{
		import ( "ORG.Util.Page" );
		$pager = new Page ( $count, $pagesize );
		
		$this->assign ( 'page', $pager->show_index() );
		return $pager;
	}	
	
}
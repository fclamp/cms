<?php
/**
 * 
 * 
 * 首页
 * @author fc_lamp
 * get_avalinks() 可用于获取所有有用的连接
 * 1幻灯片;2研究会介绍;3新闻活动;4右边栏;5会员展示'
 */
class indexAction extends baseAction
{
	
	public $index_mode = NULL;
	
	public function _initialize()
	{
		parent::_initialize();
		$this->index_mode = D('index_data');

	}
	
	
	/**
	 * 
	 * 首页展示
	 * @internal
	 * 1幻灯片;2研究会介绍;3新闻活动;4右边栏;5会员展示'
	 */
	function index()
	{
		
		$list = array();
		//幻灯、介绍、右栏 
		$tpl = $this->index_mode->where('status=1 and catid in(1,2,4)')->order('sort desc,id desc')->select();
		
		foreach ($tpl as $v)
		{
			$v['img'] = empty($v['img']) ? $this->default_img : $v['img'];
			if($v['catid']==1)
			{
				$v['title'] = my_sub_char($v['title'],28,'');
				$v['title2'] = my_sub_char($v['title2'],28,'');
				$v['abst'] = my_sub_char($v['abst'],500,'');
				$v['abst'] = str_replace('href','class="c-yellow" href',$v['abst']);
			}elseif($v['catid']==2)
			{
				$v['abst'] = str_replace('href','class="c-blue" href',$v['abst']);
			}
			
			$list[$v['catid']][] = $v;
		}
		
		$this->assign('indexInfo1',$list);
		
		
		$list = array();
		
		//新闻活动
		$tpl = $this->index_mode->where('status=1 and catid=3')->order('sort desc,id desc')->limit(6)->select();
		
		if(!empty($tpl))
		{
			//从首页内容里获取
			foreach ($tpl as $v)
			{
				$v['img'] = empty($v['img']) ? $this->default_img : $v['img'];
				
				$v['abst'] = str_replace('href','class="c-blue" href',$v['abst']);
			
				$list[$v['catid']][] = $v;
			}
			
			$top_news = $list[3][0];
			unset($list[3][0]);
			
			$this->assign('top_news',$top_news);
						
		}else
		{
			//如果不在首页内容里添加，就从普通内容里获取
			$category_mode = D ( 'category' );
			//新闻活动
			$catid = 15;
			$category_list = $this->get_cate_comm ( $category_mode,$catid, 0, True);			
			if(!empty($category_list))
			{
				$category_listids = $this->display_tree_ids($category_list);
				$catid = $category_listids . $catid;
			}
			$tpl = $this->article_mode->where("status=1 and catid in($catid)")->order('sort desc,id desc')->limit(6)->select();
			foreach ($tpl as $v)
			{
				$v['img'] = empty($v['img']) ? $this->default_img : $v['img'];
				
				$v['abst'] = str_replace('href','class="c-blue" href',$v['abst']);
				
				$v['url'] = $this->show_url.$v['id'];
				
				//转成首页内容模型
				$list[3][] = $v;
			}
			
			$top_news = $list[3][0];
			unset($list[3][0]);
			$this->assign('top_news',$top_news);
		}
		
		
		
		//会员展示
		$tpl = $this->index_mode->where('status=1 and catid=5')->order('sort desc,id desc')->select();
		if(!empty($tpl))
		{
			//从首页内容里获取
			foreach ($tpl as $v)
			{
				$v['img'] = empty($v['img']) ? $this->default_img : $v['img'];
				
				$list[$v['catid']][] = $v;
			}
						
		}else
		{			
			//会员展示(从普通内容)
			$category_mode = D ( 'category' );
			$catid = 10;
			$category_list = $this->get_cate_comm ( $category_mode,$catid, 0, True);
			if(!empty($category_list))
			{
				$category_listids = $this->display_tree_ids($category_list);
				$catid = $category_listids . $catid;
			}
			$tpl = $this->article_mode->where("status=1 and catid in($catid)")->order('sort desc,id desc')->limit(16)->select();
			
			foreach ($tpl as $v)
			{
				$v['img'] = empty($v['img']) ? $this->default_img : $v['img'];
				
				$v['abst'] = str_replace('href','class="c-blue" href',$v['abst']);
		
				$v['url'] = $this->show_url.$v['id'];
				
				//转成首页内容模型
				$list[5][] = $v;
			}
		
		}
		
		$this->assign('indexInfo2',$list);		
		
		$this->assign('is_index','yes');
		
		$this->display ();
	}
	
	/**
	 * 
	 * 列表页
	 * @internal
	 * 
	 */
	public function lists()
	{
		clean_xss ( $_GET );
		
		$category_mode = D ( 'category' );
		$category_list = $this->get_cate_comm ( $category_mode, 0, 0, True );		
		
		//当前分类
		$category = $this->getCategoryInfo ($category_list);
		
		//是否是单页栏目
		if ($category ['type'] == 2)
		{
			//则直接呈现文章
			$info = $this->getInfo ( $category );
			if (empty ( $info))
			{
				$this->error_404 ();
			}			
			$this->assign ( 'info', $info );
			$this->assign ( 'page_title', $info ['title'] );
			$this->display ( 'showPage' );
		
		} else
		{
			//获取面包路径
			$category_mode = D ( 'category' );
			$uri = $this->cateUri ( $category_mode, $category['id'] );

			$this->assign('show_page_uri',$uri);
			
			$where = 'status=1';
			//获取列表内容
			$dids = $this->display_tree_ids( $this->get_cate_comm ( $category_mode,$category['id'], 0, True ));
			$dids .= $category['id'];
			$where .= " and catid in($dids)";	
			//查询
			$article_table = $this->article_mode->getTableName ();
			$article_list = array ();
			$sql = "select count(id) as num from $article_table where $where ";
			//echo $sql;
			$count = $this->article_mode->query ( $sql );
			if (! empty ( $count [0] ['num'] ))
			{
				$p = $this->pager ( $count [0] ['num'], 6 );
				$order = 'sort desc,push_time desc';
				$limit = $p->firstRow . ',' . $p->listRows;
				$sql = "select id,catid,title,push_time,abst,img,author,attachment from $article_table where $where  order by $order limit $limit ";
				
				$list = $this->article_mode->query ( $sql );
				
				foreach ($list as $k=>$v)
				{
					$v['push_time'] = date('Y-m-d H:i:s',$v['push_time']);
					$v['title'] = htmlspecialchars(my_sub_char($v['title'],69));
					$v['url'] = $this->show_url.$v['id'];
					$v['abst'] = my_sub_char($v['abst'],260);
					if(!empty($v['attachment']))
					{
						$v['attachment'] = ROOT_URL.$v['attachment'];
					}
					if(empty($v['img']))
					{
						$v['img'] = $this->default_img;
					}
					
					
					$list[$k] = $v;
				}
				
				$this->assign ( 'list', $list );
			}			
			
			
			//呈现列表
			$this->assign ( 'page_title', $category ['name'] );
			$this->display ();
		}
	}
	
	/**
	 * 详情页
	 * 
	 * @internal
	 * 
	 */
	public function showPage()
	{
		
		$info = $this->getInfo ();
		if (empty ( $info ))
		{
			$this->error_404 ();
		}
		
		$this->assign ( 'info', $info );
		$this->assign ( 'page_title', $info ['title'] );
		$this->display ();
	
	}
	
	/**
	 * 
	 * 获取文章内容。
	 * @param $category 
	 */
	private function getInfo($category = False)
	{
		clean_xss ( $_GET );
		$where = 'a.id=b.article_id';
		if (! $category)
		{
			$id = id ( $_GET ['id'] );
			$where .= " and a.id=$id";
		
		} else
		{
			$where .= '  and a.catid=' . $category ['id'];
		
		}
		//如果是管理员	
		if (isset ( $_SESSION ['admin_info'] ['id'] ))
		{
			//Noting	
		} else
		{
			$where .= ' and a.status=1';
		}
		
		$article_table = $this->article_mode->getTableName ();
		$article_data_table = $this->article_data_mode->getTableName ();
		$article_list = array ();
		$sql = "select a.*,b.info from $article_table a,$article_data_table b where $where order by sort desc limit 1 ";
		
		$info = $this->article_mode->query ( $sql );
		
		$info = reset ( $info );
		
		if (! empty ( $info ))
		{
			$info['push_time'] = date('Y-m-d H:i:s',$info['push_time']);
			
			//栏目
			if ($category)
			{
			
			} else
			{
				$_GET ['cateid'] = $info ['catid'];
				$category_mode = D ( 'category' );
				$category_list = $this->get_cate_comm ( $category_mode, 0, 0, True );			
				$this->getCategoryInfo ($category_list);
			}
			//获取面包路径
			$category_mode = D ( 'category' );
			$uri = $this->cateUri ( $category_mode, $_GET ['cateid'],$info['title']);
			
			$this->assign('show_page_uri',$uri);
			
			
			//获取上条
			$more = array ();
			$where = 'status=1 and id < ' . $info ['id'] . ' and catid=' . $info ['catid'];
			$sql = "select id,title from $article_table where $where order by id desc limit 1 ";
			$tpl = $this->article_mode->query ( $sql );
			if (! empty ( $tpl [0] ))
			{
				$more ['up'] = reset ( $tpl );
			}
			
			//下条
			$where = 'status=1 and id > ' . $info ['id'] . ' and catid=' . $info ['catid'];
			$sql = "select id,title from $article_table where $where order by id asc limit 1  ";
			
			$tpl = $this->article_mode->query ( $sql );
			if (! empty ( $tpl [0] ))
			{
				$more ['down'] = reset ( $tpl );
			}
			$this->assign ( 'more', $more );
		}
		
		return $info;
	}
	
	/**
	 * 
	 * 获取分类信息(根据ID，如果没有直接返回404页面)
	 * 
	 * @internal
	 * 
	 */
	private function getCategoryInfo($category_list)
	{
		$category_list_data = $this->display_tree_all ( $category_list );
		if (! array_key_exists ( $_GET ['cateid'], $category_list_data ))
		{
			$this->error_404 ();
		}
		
		$this->assign ( 'cate_list', $category_list );
		return $category_list_data [$_GET ['cateid']];
	}
	
	/**
	 * 
	 * 分类面包路径
	 * @internal
	 * &raquo;<a href="#">新闻活动</a>&raquo;<span>成都市成华区推进“全国和谐教育实验区”建设 </span>
	 */
	private function cateUri($category_mode, $c_id = -1,$title='',$level=0)
	{
		$uri = '';
		$field = 'id,name,pid';
		
		$list = array ();
		$res = $category_mode->field ( $field )->where ( 'id=' . $c_id . ' and `status`=1' )->find ();
		if (empty ( $res ))
		{
			return $uri;
		}
		if ($res ['pid'] <= 0)
		{
			if($level==0)
			{
				if (!empty($title))
				{
					$uri .= '&raquo;<a href="'.$this->list_url. $res ['id'] . '">' . $res ['name'] . '</a>';
					$uri .= '&raquo;<span>' . $title . ' </span>';
				} else
				{
					$uri .= '&raquo;<span>' . $res ['name'] . ' </span>';
				}				
			}else
			{
				$uri .= '&raquo;<a href="' .$this->list_url.$res ['id'] . '">' . $res ['name'] . '</a>';
			}			
			return $uri;
		}else
		{
			if (!empty($title))
			{
				$uri .= '&raquo;<a href="' .$this->list_url.$res ['id'] . '">' . $res ['name'] . '</a>';
				$uri .= '&raquo;<span>' . $title . ' </span>';
			} else
			{
				$uri .= '&raquo;<span>' . $res ['name'] . ' </span>';
			}
			
			//找上级
			$level++;
			$r = $this->cateUri ( $category_mode, $res ['pid'],$title,$level);
			$uri = $r . $uri;			
		}
		return $uri;
	}

}
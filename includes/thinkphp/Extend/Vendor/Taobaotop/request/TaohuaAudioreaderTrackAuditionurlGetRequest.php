<?php
/**
 * TOP API: taobao.taohua.audioreader.track.auditionurl.get request
 *
 * @author auto create
 * @since 1.0, 2012-02-07 12:35:56
 */
class TaohuaAudioreaderTrackAuditionurlGetRequest
{
	/**
	 * 单曲商品ID
	 **/
	private $itemId;

	private $apiParas = array();

	public function setItemId($itemId)
	{
		$this->itemId = $itemId;
		$this->apiParas["item_id"] = $itemId;
	}

	public function getItemId()
	{
		return $this->itemId;
	}

	public function getApiMethodName()
	{
		return "taobao.taohua.audioreader.track.auditionurl.get";
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function check()
	{

		RequestCheckUtil::checkNotNull($this->itemId,"itemId");
	}
}

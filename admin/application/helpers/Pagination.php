<?php
namespace admin\application\helpers;

class Pagination
{
	const DEFAULT_PAGE_SIZE=10;

	public $pageVar='page';

	public $validateCurrentPage=true;

	private $_pageSize=self::DEFAULT_PAGE_SIZE;

	private $_itemCount=0;

	private $_currentPage;

	/**
	 * Constructor.
	 * @param integer $itemCount total number of items.
	 */
	public function __construct($itemCount=0, $pageSize = self::DEFAULT_PAGE_SIZE)
	{
		$this->setItemCount($itemCount);

		$this->setPageSize($pageSize);
	}


	public function getPageSize()
	{
		return $this->_pageSize;
	}


	public function setPageSize($value)
	{
		if(($this->_pageSize=$value)<=0) $this->_pageSize=self::DEFAULT_PAGE_SIZE;
	}


	public function getItemCount()
	{
		return $this->_itemCount;
	}


	public function setItemCount($value)
	{
		if(($this->_itemCount=$value)<0) $this->_itemCount=0;
	}


	public function getPageCount()
	{
		return (int)(($this->_itemCount+$this->_pageSize-1)/$this->_pageSize);
	}


	public function getCurrentPage($recalculate=true)
	{
		if($this->_currentPage===null || $recalculate)
		{
			if(isset($_GET[$this->pageVar]))
			{
				$this->_currentPage=(int)$_GET[$this->pageVar]-1;

				if($this->validateCurrentPage)
				{
					$pageCount=$this->getPageCount();
					if($this->_currentPage>=$pageCount)
						$this->_currentPage=$pageCount-1;
				}

				if($this->_currentPage<0)
					$this->_currentPage=0;
			}
			else
				$this->_currentPage=0;
		}
		return $this->_currentPage;
	}


	public function setCurrentPage($value)
	{
		$this->_currentPage=$value;
		$_GET[$this->pageVar]=$value+1;
	}


	public function applyLimit()
	{
		return 'LIMIT '.$this->getOffset().', '.$this->getLimit();
	}

	/**
	 * @return integer the offset of the data. This may be used to set the
	 * OFFSET value for a SQL statement for fetching the current page of data.
	 * @since 1.1.0
	 */
	public function getOffset()
	{
		return $this->getCurrentPage()*$this->getPageSize();
	}

	/**
	 * @return integer the limit of the data. This may be used to set the
	 * LIMIT value for a SQL statement for fetching the current page of data.
	 * This returns the same value as {@link pageSize}.
	 * @since 1.1.0
	 */
	public function getLimit()
	{
		return $this->getPageSize();
	}

	public function getTemplate() {

		return [
			'pageVar'=>$this->pageVar,
			'pageSize'=>$this->getPageSize(),
			'itemCount'=>$this->getItemCount(),
			'currentPage'=>$this->getCurrentPage()+1,
			'pageCount'=>$this->getPageCount(),
			'offset'=>$this->getOffset(),
			'limit'=>$this->getLimit(),

		];
	}
}
<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

//require_once 'goutte.phar';
require_once(ROOT . DS  . 'vendor' . DS  . 'goutte' . DS . 'goutte.phar');
use Goutte\Client;



class ScrapEnvironmentalExpertShell extends Shell
{
    
	public $company_info 	= array();
	public $page_no			= 1;

	public $Description_Blocks 	= array();
	public $block_count			= 0;

    public function main()
    {
    	$this->page_no = 1;
        //$this->out('Hello world Enviroment Expert.');

        $client = new Client();

        $guzzle = $client->getClient();
		//$guzzle->setDefaultOption('proxy', '127.0.0.1:9050');
		$client->setClient($guzzle);


		while(true) {

	        if($this->page_no < 2) {
	        	$page_url	= 'http://www.environmental-expert.com/companies';
	        } else {
	        	$page_url	= 'http://www.environmental-expert.com/companies/page-'.$this->page_no;
	        }
	        echo "\r\nPAGE URL ::".$page_url."==DATE=>".date('Y-m-d H:i:s');
	        $crawler = $client->request('GET', $page_url);
	        
	        // Get All the company names from the listing page.
			$company_names = $crawler->filter('h2 > a')->each(function ($node) {
	    						return $node->text()."\n";
							});

			//$company_names = array('Spraying Equipment Suppliers');

			if(is_array($company_names) && count($company_names)>0) {

				foreach ($company_names as $key => $company_name) {
					# code...
					$this->Description_Blocks	= array();
					$this->block_count			= 0;

					//SET DEFAULT VALUES
					$company_info				= array('company_name' => '',
														'url' => '',
														'logo_link' => '',
														'street_addr' => '',
														'addLocality' => '',
														'addr_region' => '',
														'postalCode' => '',
														'country' => '',
														'website' => '',
														'description' => '',
														'industries_served' => array(),
														'desc_blocks' => array(),
														'business_type' => '',
														'industry_type' => '',
														'market_focus' => '',
														'year_founded' => '',
														'employee_cnt' => '',
														'turnover' => '',
														);


					$c_name 	= trim($company_name);
					$click_comapny_name	= trim(preg_replace('/-.*/', '', $c_name));

					if($c_name == 'Verderflex Peristaltic Pumps -  part of the Verder Group') {
						//continue;
					}

					echo "\r\nCompany Name ::".$c_name."<====>".$click_comapny_name."<=>";
					if($crawler->selectLink($c_name)->count()>0) {
						$link1 		= $crawler->selectLink($c_name)->link();
					} else {
						if($crawler->selectLink($click_comapny_name)->count()>0) {
							$link1 		= $crawler->selectLink($click_comapny_name)->link();
						} else {
							continue;
						}
					}

					$Uri		= $link1->getUri();
					echo $Uri;
					//$Uri 		= 'http://www.environmental-expert.com/companies/ionicon-analytik-ges-m-b-h-23351';

					if(!$this->CheckCompanyExists($Uri, $c_name)) {

						$crawler1 	= $client->click($link1);

						/*$response = $client->getResponse();
						echo $response->getContent();
						die;*/
						// $c_name = 'METIS Scientific';
						$crawler1 = $client->request('GET', $Uri);

						$status_code = $client->getResponse()->getStatus();

						if($status_code == 200) {

							$company_info['company_name']	= $c_name;
							$company_info['url'] 			= $Uri;
							$page_content					= $crawler1->html();
							if($crawler1->filter('div.storefront_box_logo > a > img')->count()>0) {
								$company_info['logo_link'] 		= $crawler1->filter('div.storefront_box_logo > a > img')->attr('src');	
							}							

							if($crawler1->filter('span[itemprop="streetAddress"]')->count()>0) {
								$company_info['street_addr']	= $crawler1->filter('span[itemprop="streetAddress"]')->text();
							}

							if($crawler1->filter('span[itemprop="addressLocality"]')->count()>0) {
								$company_info['addLocality']	= $crawler1->filter('span[itemprop="addressLocality"]')->text();
							}
							
							//For Address Region
							if($crawler1->filter('span[itemprop="addressRegion"],a[itemprop="addressRegion"]')->count()>0) {
								$addressRegion = $crawler1->filter('span[itemprop="addressRegion"],a[itemprop="addressRegion"]')->each(function ($node) {
										    								return $node->text()."\n";
																		});
								if(isset($addressRegion[0])) {
									$company_info['addr_region'] = $addressRegion[0];
								}
							}

							if($crawler1->filter('span[itemprop="postalCode"]')->count()>0) {
								$company_info['postalCode'] = $crawler1->filter('span[itemprop="postalCode"]')->text();	
							}

							if($crawler1->filter('a[itemprop="addressCountry"]')->count()>0) {
								$company_info['country'] 	= $crawler1->filter('a[itemprop="addressCountry"]')->text();	
							}						

							//Address 2nd variation found
							if($crawler1->filter('div.col_three_fourth_storefront > div > div > p')->count()>0) {
								$address_block	= $crawler1->filter('div.col_three_fourth_storefront > div > div > p')->html();

								preg_match("@([^<]+)<a@si", $address_block, $add_city);
								if(is_array($add_city) && isset($add_city[1]) && !empty($add_city[1])) {
									$address_city 	= $add_city[1];
									$add_detail 	= explode(',', $address_city);
									if(count($add_detail)==3) {
										$company_info['street_addr']	= $add_detail[0];
										preg_match("@#\s(\d+)\s+(.+)@si", $add_detail[1], $city_unit);
										if(count($city_unit)>0) {
											$company_info['unit']			= $city_unit[1];
											$company_info['addLocality']	= $city_unit[2];
										}
									}
								}
								preg_match_all("@location-([^<]+)>([^<]+)</a@si", $address_block, $state_country);
								if(isset($state_country[2]) && count($state_country[2])>0) {
									if(count($state_country[2])==2) {
										$company_info['addr_region'] 	= $state_country[2][0];
										$company_info['country'] 		= $state_country[2][1];
									}
								}
								preg_match("@\|</span>\s(\d+)@si", $address_block, $postalcode);
								if(isset($postalcode[1])) {
									$company_info['postalCode'] = $postalcode[1];
								}														
							}							
							
							if($crawler1->selectLink('Visit Website')->count()>0) {
								$website 					= $crawler1->selectLink('Visit Website')->extract(array('href'));
								$company_info['website'] 	= $website[0];	
							}
							

							if($crawler1->filter('div[itemprop="description"]')->count()>0) {
								$company_info['description']= $crawler1->filter('div[itemprop="description"]')->text();	
							}							
							//echo "\r\n\r\n";
							
							// Code to get Industry Served Categories
							preg_match('@<div class="promo-desc"><h2>INDUSTRIES SERVED</h2></div>\s+</div>\s+<div[^>]*>\s+(.*?)</div>@si', $page_content, $industries_served);
							if(isset($industries_served[0]) && !empty($industries_served[0])) {
								preg_match_all('@<a[^>]*>(.*?)</a>@si', $industries_served[0], $indus_served);
								if(isset($indus_served[1])) {
									$company_info['industries_served']	= $indus_served[1];
								}
							}
							// if($crawler1->filter('div.clearfix > div.col_full > a')->count()>0) {
							// 	$company_info['industries_served']	= $crawler1->filter('div.clearfix > div.col_full > a')->each(function ($node) {
									    								
							// 		    								return $node->text()."\n";
							// 										});	
							// }

							$company_detail = array();

							if($crawler1->filter('div.cp_details > div > p.mbottom10')->count()>0) {
								$company_detail = $crawler1->filter('div.cp_details > div > p.mbottom10')->each(function ($node) {
										    								return $node->html();									    								
																		});
							}

							//print_r($company_detail);
							if($crawler1->filter('div.col_full.content_list')->count() > 0) {						
								$abc = $crawler1->filter('div.col_full.content_list')->each(function ($node) {
										    								//return $node->text()."\n";
																			$remove_title 		= '';
																			$remove_subtitle	= '';
																			$this->Description_Blocks[$this->block_count]['title']		= '';
																			$this->Description_Blocks[$this->block_count]['sub_title']	= '';

																			if($node->filter('div > div.promo-desc')->count() > 0) {

										    									$this->Description_Blocks[$this->block_count]['title']	= $node->filter('div > div.promo-desc')->text();
										    								}

										    								if($node->filter('div.entry_share.clearfix')->count() > 0) {

										    									$this->Description_Blocks[$this->block_count]['sub_title']	= $node->filter('div.entry_share.clearfix > span > strong')->text();
										    								}

										    								if(isset($this->Description_Blocks[$this->block_count]['title']) && !empty($this->Description_Blocks[$this->block_count]['title'])) {
										    									$remove_title = '\s+<div class="promo">\s+<div class="promo-desc"><h2>'.$this->Description_Blocks[$this->block_count]['title'].'</h2></div>\s+</div>\s+';
										    								}
										    								//echo $remove_title;

										    								if(isset($this->Description_Blocks[$this->block_count]['sub_title']) && !empty($this->Description_Blocks[$this->block_count]['sub_title'])) {
										    									$remove_subtitle	= '(\s?)+<div class="entry_share clearfix">\s+<span><strong>'.$this->Description_Blocks[$this->block_count]['sub_title'].'</strong></span>\s+</div>';
										    								}

										    								$des_block = $node->html();
										    								$this->Description_Blocks[$this->block_count]['full_html']	= $des_block;
									    									if(!empty($remove_title)) {
									    										$des_block = preg_replace("@".$remove_title."@", '', $des_block);
									    									}
									    									if(!empty($remove_subtitle)) {
									    										$des_block = preg_replace("@".$remove_subtitle."@", '', $des_block);	
									    									}
									    									$this->Description_Blocks[$this->block_count]['description']= $des_block;
										    								$this->block_count++;
																		});
							}

							$company_info['desc_blocks'] = $this->Description_Blocks;

							if(is_array($company_detail) && count($company_detail) >0 ) {

								$business_type 	= '';
								$industry_type 	= '';
								$market_focus 	= '';
								$year_founded	= '';
								$employee_cnt	= '';
								$turnover		= '';

								foreach ($company_detail as $key => $c_detail) {
									//For Business Type
									if(empty($business_type)) {
										preg_match('#<span><b>Business Type:</b></span><br>(.*)#', $c_detail, $arr_match);
										if(is_array($arr_match) && count($arr_match)>0 && isset($arr_match[1])) {
											$business_type = $arr_match[1];
											continue;
										}
									}

									//For Industry Type
									if(empty($industry_type)) {
										preg_match('#<span><b>Industry Type:</b></span><br>(.*)#', $c_detail, $arr_match);
										if(is_array($arr_match) && count($arr_match)>0 && isset($arr_match[1])) {
											$industry_type = $arr_match[1];
											continue;
										}
									}

									//For Market Focus
									if(empty($market_focus)) {
										preg_match('#<span><b>Market Focus:</b></span><br>(.*)#', $c_detail, $arr_match);
										if(is_array($arr_match) && count($arr_match)>0 && isset($arr_match[1])) {
											$market_focus = $arr_match[1];
											continue;
										}
									}

									//For Year Founded
									if(empty($year_founded)) {
										preg_match('#<span><b>Year Founded:</b></span><br>(.*)#', $c_detail, $arr_match);
										if(is_array($arr_match) && count($arr_match)>0 && isset($arr_match[1])) {
											$year_founded = $arr_match[1];
											continue;
										}
									}

									//For Employee
									if(empty($employee_cnt)) {
										preg_match('#<span><b>Employees:</b></span><br>(.*)#', $c_detail, $arr_match);
										if(is_array($arr_match) && count($arr_match)>0 && isset($arr_match[1])) {
											$employee_cnt = $arr_match[1];
											continue;
										}
									}

									//For Turnover
									if(empty($turnover)) {
										preg_match('#<span><b>Turnover:</b></span><br>(.*)#', $c_detail, $arr_match);
										if(is_array($arr_match) && count($arr_match)>0 && isset($arr_match[1])) {
											$turnover = $arr_match[1];
											continue;
										}
									}
								}					
							}

							$company_info['business_type']	= $business_type;
							$company_info['industry_type']	= $industry_type;
							$company_info['market_focus']	= $market_focus;
							$company_info['year_founded']	= $year_founded;
							$company_info['employee_cnt']	= $employee_cnt;
							$company_info['turnover']		= $turnover;

							//print_r($company_info);
							//die;
							$this->SaveCompany($company_info);
							
						} else {
							echo "\r\nCompany data not found :: ".$c_name."==URL==>";
						}
					}

				}
			}
			$this->page_no++;
		}		
    }

    private function CheckCompanyExists($Uri, $company_name)
    {
    	# code...
    	$companiesTable = TableRegistry::get('Companies');

    	$data = [
            'company_name' => $company_name,
            'env_expert_url'=> $Uri
        ];

        $exists = $companiesTable->exists($data);

        if($exists) {
        	return true;
        }
        return false;
    }

    private function SaveCompanyDescriptionBlocks($company_id, $company_des_blocks)
    {
    	# code...
    	if(is_array($company_des_blocks) && count($company_des_blocks)>0) {

    		$CompanyDescBlocksTable = TableRegistry::get('CompanyDescBlocks');

    		foreach ($company_des_blocks as $desc_block) {
    			# code...
    			$CompanyDescBlock = $CompanyDescBlocksTable->newEntity();

    			$CompanyDescBlock->company_id 	= $company_id;
				$CompanyDescBlock->type 		= 1;
				$CompanyDescBlock->title 		= trim($desc_block['title']);
				$CompanyDescBlock->sub_title 	= trim($desc_block['sub_title']);
				$CompanyDescBlock->description 	= trim($desc_block['description']);
				$CompanyDescBlock->md5			= md5($desc_block['full_html']);
				$CompanyDescBlock->created 		= date('Y-m-d H:i:s');

				$CompanyDescBlocksTable->save($CompanyDescBlock);
    		}	
    	}
    }

    private function SaveCompnayAddress($company_id, $company_detail, $primary_add=1)
    {
    	# code...
    	$AddressesTable = TableRegistry::get('Addresses');

    	$address = $AddressesTable->newEntity();

    	$address->company_id	= $company_id;
		$address->street 		= $company_detail['street_addr'];
		$address->address1 		= $company_detail['street_addr'];
		$address->city 			= $company_detail['addLocality'];
		$address->state 		= $company_detail['addr_region'];
		$address->postalcode 	= $company_detail['postalCode'];
		$address->country 		= $company_detail['country'];
		$address->primary_add 	= $primary_add;		
		$address->address_md5 	= md5($address->street.$address->address1.$address->state.$address->postalcode.$address->country);
		$address->created 		= date('Y-m-d H:i:s');

		if ($AddressesTable->save($address)) {
		    $id = $address->id;
		    return $id;
		} else {
			return 0;
		}
    }

    private function SaveCompany($company_detail)
    {
    	$companiesTable = TableRegistry::get('Companies');

    	$data = [
            'company_name' => $company_detail['company_name'],
            'env_expert_url' => $company_detail['url'],
        ];

        $exists = $companiesTable->exists($data);

        if(!$exists) {

        	$company = $companiesTable->newEntity();

        	$business_type_id		= $this->GetBusinessTypeByName($company_detail['business_type']);
        	$industry_type_id		= $this->GetIndustryTypeByName($company_detail['industry_type']);
        	$market_focus_id		= $this->GetMarketFocusByName($company_detail['market_focus']);

			$company->company_name 	= $company_detail['company_name'];
			$company->website 		= $company_detail['website'];
			$company->description 	= $company_detail['description'];
			$company->logo 			= $company_detail['logo_link'];
			$company->business_type = $business_type_id;
			$company->industry_type	= $industry_type_id;
			$company->market_focus 	= $market_focus_id;
			$company->year_founded 	= $company_detail['year_founded'];
			$company->employee_cnt 	= $company_detail['employee_cnt'];
			$company->turnover 		= $company_detail['turnover'];
			$company->env_expert_url= $company_detail['url'];
			$company->status 		= 1;
			$company->page_no 		= $this->page_no;
			$company->created 		= date('Y-m-d H:i:s');


			if ($companiesTable->save($company)) {
			    $id = $company->id;
			    $this->SaveCompnayAddress($company->id, $company_detail, 1);
			    $this->SaveCompanyIndustries($company->id, $company_detail['industries_served']);
			    $this->SaveCompanyDescriptionBlocks($company->id, $company_detail['desc_blocks']);
			    return $id;
			} else {
				return 0;
			}
        }
        echo "==\r\nCompany Data Already Exists ::".$company_detail['company_name'];
    }

    private function SaveIndustry($industry_name)
    {
    	# code...
    	$IndustriesTable = TableRegistry::get('Industries');
    	$industry = $IndustriesTable->newEntity();

		$industry->industry_name 	= $industry_name;
		$industry->created 			= date('Y-m-d H:i:s');

		if ($IndustriesTable->save($industry)) {
			return $industry->id;
		}
		return 0;
    }

    private function SaveBusinessType($business_type)
    {
    	# code...
    	$BusinessTypesTable = TableRegistry::get('BusinessTypes');
    	$BusinessType = $BusinessTypesTable->newEntity();

		$BusinessType->business_type 	= $business_type;
		$BusinessType->created 			= date('Y-m-d H:i:s');

		if ($BusinessTypesTable->save($BusinessType)) {
			return $BusinessType->id;
		}
		return 0;
    }

    private function SaveIndustryType($industry_type)
    {
    	# code...
    	$IndustryTypesTable = TableRegistry::get('IndustryTypes');
    	$IndustryType = $IndustryTypesTable->newEntity();

		$IndustryType->industry_type 	= $industry_type;
		$IndustryType->created 			= date('Y-m-d H:i:s');

		if ($IndustryTypesTable->save($IndustryType)) {
			return $IndustryType->id;
		}
		return 0;
    }

    private function SaveMarketFocusType($market_focus)
    {
    	# code...
    	$MarketFocusTypesTable 	= TableRegistry::get('MarketFocusTypes');
    	$MarketFocusType 		= $MarketFocusTypesTable->newEntity();

		$MarketFocusType->market_focus 		= $market_focus;
		$MarketFocusType->created 			= date('Y-m-d H:i:s');

		if ($MarketFocusTypesTable->save($MarketFocusType)) {
			return $MarketFocusType->id;
		}
		return 0;
    }

	private function GetBusinessTypeByName($business_type)
    {
    	# code...
    	$business_type 		= trim($business_type);
    	$BusinessTypesTable = TableRegistry::get('BusinessTypes');

    	$query = $BusinessTypesTable->find('all', [
				    'conditions' => ['BusinessTypes.business_type' => $business_type]
				]);

		$row = $query->first();
		if($row) {
			return $row->id;
		}		
		return $this->SaveBusinessType($business_type);
    }

    private function GetIndustryTypeByName($industry_type)
    {
    	# code...
    	$industry_type 		= trim($industry_type);
    	$IndustryTypesTable = TableRegistry::get('IndustryTypes');

    	$query = $IndustryTypesTable->find('all', [
				    'conditions' => ['IndustryTypes.industry_type' => $industry_type]
				]);
		$row = $query->first();
		if($row) {
			return $row->id;
		}		
		return $this->SaveIndustryType($industry_type);
    }

    private function GetMarketFocusByName($market_focus)
    {
    	# code...
    	$MarketFocusTypesTable = TableRegistry::get('MarketFocusTypes');

    	$query = $MarketFocusTypesTable->find('all', [
				    'conditions' => ['MarketFocusTypes.market_focus' => $market_focus]
				]);
		$row = $query->first();
		if($row) {
			return $row->id;
		}		
		return $this->SaveMarketFocusType($market_focus);
    }    

    private function GetIndustryIDByIndustryName($industry_name)
    {
    	# code...
    	$IndustriesTable = TableRegistry::get('Industries');

    	$query = $IndustriesTable->find('all', [
				    'conditions' => ['Industries.industry_name' => $industry_name]
				]);
		$row = $query->first();
		if($row) {
			return $row->id;
		}		
		return $this->SaveIndustry($industry_name);
    }

    private function SaveCompanyIndustries($company_id, $company_industries)
    {
    	# code...
    	if(is_array($company_industries) && count($company_industries)>0) {

    		foreach ($company_industries as $company_industry) {
    			# code...
    			$industry_id	= $this->GetIndustryIDByIndustryName($company_industry);
    			if(!empty($industry_id) && !empty($company_id)) {
    				$CompanyIndustriesTable = TableRegistry::get('CompanyIndustries');

    				$data = [
					            'company_id' => $company_id,
					            'industry_id'=> $industry_id
					        ];

			        $exists = $CompanyIndustriesTable->exists($data);

			        if(!$exists) {


	    				$CompanyIndustry = $CompanyIndustriesTable->newEntity();

						$CompanyIndustry->company_id 	= $company_id;
						$CompanyIndustry->industry_id 	= $industry_id;
						$CompanyIndustry->created 		= date('Y-m-d H:i:s');
						$CompanyIndustriesTable->save($CompanyIndustry);
					}
    			}
    		}
    	}
    }
}
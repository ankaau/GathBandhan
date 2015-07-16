<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2015 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

use CBLib\Application\Application;
use CBLib\Language\CBTxt;

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

if ( Application::MyUser()->isAuthorizedToPerformActionOnAsset( 'core.manage', 'com_comprofiler' ) ) {
	if ( $params->get( 'menu_cb', 1 ) && file_exists( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler' ) ) {
		$cbMenu							=	array();
		$cbMenu['component']			=	array(	'title' => CBTxt::Th( 'Community Builder' ) );
		$cbMenu['menu']					=	array(	array(	'title' => CBTxt::Th( 'Control Panel' ), 'link' => $_CB_framework->backendViewUrl( null ), 'icon' => 'cb-control_panel' ),
													array(	'title' => CBTxt::Th( 'User Management' ), 'link' => $_CB_framework->backendViewUrl( 'showusers' ), 'access' => array( 'core.manage', 'com_users' ), 'icon' => 'cb-user_management',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New User' ), 'link' => $_CB_framework->backendViewUrl( 'new' ), 'access' => array( 'core.create', 'com_users' ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Tab Management' ), 'link' => $_CB_framework->backendViewUrl( 'showTab' ), 'access' => array( 'core.manage', 'com_comprofiler.tabs' ), 'icon' => 'cb-tab_management',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New Tab' ), 'link' => $_CB_framework->backendViewUrl( 'editrow', true, array( 'table' => 'tabsbrowser', 'action' => 'editrow' ) ), 'access' => array( array( 'core.create', 'core.edit' ), 'com_comprofiler.tabs' ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Field Management' ), 'link' => $_CB_framework->backendViewUrl( 'showField' ), 'access' => array( 'core.manage', 'com_comprofiler.fields' ), 'icon' => 'cb-field_management',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New Field' ), 'link' => $_CB_framework->backendViewUrl( 'editrow', true, array( 'table' => 'fieldsbrowser', 'action' => 'editrow' ) ), 'access' => array( array( 'core.create', 'core.edit' ), 'com_comprofiler.fields' ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'List Management' ), 'link' => $_CB_framework->backendViewUrl( 'showLists' ), 'access' => array( 'core.manage', 'com_comprofiler.lists' ), 'icon' => 'cb-list_management',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New List' ), 'link' => $_CB_framework->backendViewUrl( 'editrow', true, array( 'table' => 'listsbrowser', 'action' => 'editrow' ) ), 'access' => array( array( 'core.create', 'core.edit' ), 'com_comprofiler.lists' ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Plugin Management' ), 'link' => $_CB_framework->backendViewUrl( 'showPlugins' ), 'access' => array( 'core.manage', 'com_comprofiler.plugins' ), 'icon' => 'cb-plugin_management',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Install New Plugin' ), 'link' => $_CB_framework->backendViewUrl( 'installcbplugin' ), 'access' => array( 'core.admin', 'root' ), 'icon' => 'cb-upload' ) )
													),
													array(	'title' => CBTxt::Th( 'Tools' ), 'link' => $_CB_framework->backendViewUrl( 'tools' ), 'access' => array( 'core.manage', 'com_comprofiler.tools' ), 'icon' => 'cb-tools' ),
													array(	'title' => CBTxt::Th( 'Configuration' ), 'link' => $_CB_framework->backendViewUrl( 'showconfig' ), 'access' => array( 'core.admin', 'com_comprofiler' ), 'icon' => 'cb-configuration' ),
													array(	'title' => CBTxt::Th( 'Credits' ), 'link' => $_CB_framework->backendViewUrl( 'credits' ), 'icon' => 'cb-credits' )
												);

		$menu[]							=	$cbMenu;
	}

	if ( $params->get( 'menu_cbsubs', 1 ) && file_exists( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/user/plug_cbpaidsubscriptions' ) ) {
		$query							=	'SELECT ' . $_CB_database->NameQuote( 'id' )
										.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_plugin' )
										.	"\n WHERE " . $_CB_database->NameQuote( 'element' )	. ' = ' . $_CB_database->Quote( 'cbpaidsubscriptions' );
		$_CB_database->setQuery( $query, 0, 1 );
		$pluginId						=	$_CB_database->loadResult();

		if ( $pluginId ) {
			$cbsubsMenu					=	array();

			$cbsubsMenu['component']	=	array(	'title' => CBTxt::Th( 'Paid Subscriptions' ) );
			$cbsubsMenu['menu']			=	array(	array(	'title' => CBTxt::Th( 'Payments Center' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId ) ), 'icon' => 'cbsubs-payments_center' ),
													array(	'title' => CBTxt::Th( 'Settings' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showparams' ) ), 'access' => array( 'cbsubs.settings', 'com_cbsubs' ), 'icon' => 'cbsubs-settings' ),
													array(	'title' => CBTxt::Th( 'Gateways' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'gateways' ) ), 'access' => array( 'cbsubs.gateways', 'com_cbsubs' ), 'icon' => 'cbsubs-gateways',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New Gateway' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'editrow', 'table' => 'gatewaysbrowser' ) ), 'access' => array( 'cbsubs.gateways', 'com_cbsubs' ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Plans' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'plans' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cbsubs-plans',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New Plan' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'editrow', 'table' => 'plansbrowser' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Subscriptions' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'subscriptions' ) ), 'access' => array( 'cbsubs.usersubscriptionview', 'com_cbsubs' ), 'icon' => 'cbsubs-subscriptions' ),
													array(	'title' => CBTxt::Th( 'Baskets' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'paymentbaskets' ) ), 'access' => array( array( 'cbsubs.sales', 'cbsubs.financial' ), 'com_cbsubs' ), 'icon' => 'cbsubs-baskets' ),
													array(	'title' => CBTxt::Th( 'Payments' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'payments' ) ), 'access' => array( array( 'cbsubs.sales', 'cbsubs.financial' ), 'com_cbsubs' ), 'icon' => 'cbsubs-payments' ),
													array(	'title' => CBTxt::Th( 'Notifications' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'notifications' ) ), 'access' => array( array( 'cbsubs.settings', 'cbsubs.gateways', 'cbsubs.sales' ), 'com_cbsubs' ), 'icon' => 'cbsubs-notifications' ),
													array(	'title' => CBTxt::Th( 'Currencies' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'currencies' ) ), 'access' => array( array( 'cbsubs.marketing', 'cbsubs.financial' ), 'com_cbsubs' ), 'icon' => 'cbsubs-currencies' ),
													array(	'title' => CBTxt::Th( 'Statistics' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstats' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics',
															'submenu' => array(	array( 'title' => CBTxt::Th( 'Payments Monthly' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatsmonthly' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_payments_monthly' ),
																				array( 'title' => CBTxt::Th( 'Payments Weekly' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatsweekly' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_payments_weekly' ),
																				array( 'title' => CBTxt::Th( 'Payments by Weekday' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatsdayofweek' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_payments_weekday' ),
																				array( 'title' => CBTxt::Th( 'Payments by Hour' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatshourofday' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_payments_hourly' ),
																				array( 'title' => CBTxt::Th( 'Payments by Country' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatscountrypayments' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_payments_country' ),
																				array( 'title' => CBTxt::Th( 'Payments Free Query' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatsfreequery' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_payments_query' ),
																				array( 'title' => CBTxt::Th( 'Sales Monthly' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatsitemsmonthly' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_sales_monthly' ),
																				array( 'title' => CBTxt::Th( 'Sales Weekly' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatsitemsweekly' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_sales_weekly' ),
																				array( 'title' => CBTxt::Th( 'Sales by Weekday' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showstatsitemsdayofweek' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-statistics_sales_weekday' )
																			)
													),
													array(	'title' => CBTxt::Th( 'Merchandise' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'merchandises' ) ), 'access' => array( 'cbsubs.merchandisemanage', 'com_cbsubs' ), 'icon' => 'cbsubs-merchandise' ),
													array(	'title' => CBTxt::Th( 'Donations' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'donations' ) ), 'access' => array( 'cbsubs.donationview', 'com_cbsubs' ), 'icon' => 'cbsubs-donations' ),
													array(	'title' => CBTxt::Th( 'Import' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'import' ) ), 'access' => array( array( 'cbsubs.settings', 'cbsubs.recordpayments' ), 'com_cbsubs' ), 'icon' => 'cbsubs-import' ),
													array(	'title' => CBTxt::Th( 'History Logs' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'history' ) ), 'access' => array( array( 'cbsubs.settings', 'cbsubs.gateways' ), 'com_cbsubs' ), 'icon' => 'cbsubs-history_logs' )
												);

			if ( file_exists( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/user/plug_cbpaidsubscriptions/plugin/cbsubstax' ) ) {
				$cbsubsMenu['menu'][]	=	array(	'title' => CBTxt::Th( 'Taxes' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtaxsettings' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-taxes',
													'submenu' => array(	//array( 'title' => CBTxt::Th( 'Tax Rates' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'taxrules' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-taxes_rules' ),
																		//array( 'title' => CBTxt::Th( 'Tax Rules' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'taxrates' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-taxes_rates' ),
																		//array( 'title' => CBTxt::Th( 'Geographic Zones' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'geozones' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-taxes_zones' ),
																		//array( 'title' => CBTxt::Th( 'Countries' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'countries' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-taxes_countries' ),
																		//array( 'title' => CBTxt::Th( 'States / Provinces' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'provinces' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-taxes_states' ),
																		array( 'title' => CBTxt::Th( 'Sales Tax / VAT Report' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'taxreportmonthly' ) ), 'access' => array( 'cbsubs.financial', 'com_cbsubs' ), 'icon' => 'cbsubs-taxes_report' )
																	)
												);
			}

			if ( file_exists( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/user/plug_cbpaidsubscriptions/plugin/cbsubspromotion' ) ) {
				$cbsubsMenu['menu'][]	=	array(	'title' => CBTxt::Th( 'Promotions' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showpromotionssettings' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cbsubs-promotions',
													'submenu' => array(	array( 'title' => CBTxt::Th( 'Add New Promotion' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'editrow', 'table' => 'promotionsbrowser' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cb-new' ),
																		//array( 'title' => CBTxt::Th( 'Promotions Settings' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'promotions' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cbsubs-promotions_settings' ),
																		array( 'title' => CBTxt::Th( 'Promotions Used' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'promotionsuses' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cbsubs-promotions_used' ),
																		array( 'title' => CBTxt::Th( 'Promotions Statistics' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'promotionsstats' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cbsubs-promotions_statistics' )
																	)
												);
			}

			if ( file_exists( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/user/plug_cbpaidsubscriptions/plugin/cbsubsmailer' ) ) {
				$cbsubsMenu['menu'][]	=	array(	'title' => CBTxt::Th( 'Mailer' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showmailersettings' ) ), 'access' => array( array( 'core.admin', 'cbsubs.marketing' ), 'com_cbsubs' ), 'icon' => 'cbsubs-mailer',
													'submenu' => array(	array( 'title' => CBTxt::Th( 'Add New Mailer' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'editrow', 'table' => 'mailersbrowser' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cb-new' ),
																		array( 'title' => CBTxt::Th( 'Messages Queue' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'mailermailqueue' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cbsubs-mailer_queue' ),
																		array( 'title' => CBTxt::Th( 'Sent Messages' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'showtable', 'table' => 'mailersentmessages' ) ), 'access' => array( 'cbsubs.marketing', 'com_cbsubs' ), 'icon' => 'cbsubs-mailer_sent' )
																	)
												);
			}

			$menu[]						=	$cbsubsMenu;
		}
	}

	if ( $params->get( 'menu_cbgj', 1 ) && file_exists( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/user/plug_cbgroupjive' ) ) {
		$query							=	'SELECT ' . $_CB_database->NameQuote( 'id' )
										.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_plugin' )
										.	"\n WHERE " . $_CB_database->NameQuote( 'element' )	. ' = ' . $_CB_database->Quote( 'cbgroupjive' );
		$_CB_database->setQuery( $query, 0, 1 );
		$pluginId						=	$_CB_database->loadResult();

		if ( $pluginId ) {
			$gjMenu						=	array();

			$gjMenu['component']		=	array(	'title' => CBTxt::Th( 'GroupJive' ) );
			$gjMenu['menu']				=	array(	array(	'title' => CBTxt::Th( 'Plugin' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId ) ), 'icon' => 'cbgj-plugin' ),
													array(	'title' => CBTxt::Th( 'Categories' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'categories' ) ), 'icon' => 'cbgj-categories',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New Category' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'categories.new' ) ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Groups' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'groups' ) ), 'icon' => 'cbgj-groups',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New Group' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'groups.new' ) ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Users' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'users' ) ), 'icon' => 'cbgj-users',
															'submenu' => array( array( 'title' => CBTxt::Th( 'Add New User to Group' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'users.new' ) ), 'icon' => 'cb-new' ) )
													),
													array(	'title' => CBTxt::Th( 'Invites' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'invites' ) ), 'icon' => 'cbgj-invites' ),
													array(	'title' => CBTxt::Th( 'Configuration' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'config' ) ), 'icon' => 'cbgj-configuration' ),
													array(	'title' => CBTxt::Th( 'Tools' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'tools' ) ), 'icon' => 'cbgj-tools' ),
													array(	'title' => CBTxt::Th( 'Integrations' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'integrations' ) ), 'icon' => 'cbgj-integrations' ),
													array(	'title' => CBTxt::Th( 'Menus' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'menus' ) ), 'icon' => 'cbgj-menus' )
												);

			if ( file_exists( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/user/plug_cbgroupjive/plugins/cbgroupjiveauto' ) ) {
				$gjMenu['menu'][]		=	array( 'title' => CBTxt::Th( 'Auto' ), 'link' => $_CB_framework->backendViewUrl( 'editPlugin', true, array( 'cid' => $pluginId, 'action' => 'plugin.auto' ) ), 'icon' => 'cbgj-auto' );
			}

			$menu[]						=	$gjMenu;
		}
	}

	if ( $params->get( 'menu_plugins', 1 ) ) {
		$_PLUGINS->loadPluginGroup( 'user' );

		$_PLUGINS->trigger( 'mod_onCBAdminMenu', array( &$menu, $disabled ) );
	}
}
?>
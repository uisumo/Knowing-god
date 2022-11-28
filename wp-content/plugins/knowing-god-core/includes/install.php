<?php
/**
 * Knowing God Install Functions
 *
 * @package		Knowing God
 * @category	Class
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'knowing_god_db_install' );
add_action( 'admin_init', 'knowing_god_install_pages' );
add_action( 'admin_init', 'knowing_god_default_templates' );

/**
 * Function to install the required database tables.
 *
 * @since 2.0.0
*/
function knowing_god_db_install() {
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );

	$kg_countries = $wpdb->prefix . 'kg_countries';
	$db_version = '1.0.0';

	$charset = 'utf8';
	$collate = 'utf8_general_ci';

	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$charset = $wpdb->charset;
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate = $wpdb->collate;
		}
	}	

	$db_version_countries = '1.0.0';
	$kg_countries_table = "CREATE TABLE `$kg_countries` (
	  `id_countries` int(3) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(200) COLLATE $collate DEFAULT NULL,
	  `iso_alpha2` varchar(2) COLLATE $collate DEFAULT NULL,
	  `iso_alpha3` varchar(3) COLLATE $collate DEFAULT NULL,
	  `iso_numeric` int(11) DEFAULT NULL,
	  `currency_code` char(3) COLLATE $collate DEFAULT NULL,
	  `currency_name` varchar(32) COLLATE $collate DEFAULT NULL,
	  `currency_symbol` varchar(50) COLLATE $collate DEFAULT NULL,
	  `flag` varchar(6) COLLATE $collate DEFAULT NULL,
	  `phonecode` int(6) DEFAULT NULL,
	  PRIMARY KEY (`id_countries`)
	) ENGINE=MyISAM DEFAULT CHARSET=$charset COLLATE=$collate;";
	dbDelta( $kg_countries_table );
	$previous_version = get_option( $kg_countries . '_db_version' );
	update_option( $kg_countries . '_db_version', $db_version_countries );
	
	$kg_countries_data = "INSERT INTO `$kg_countries` (`id_countries`, `name`, `iso_alpha2`, `iso_alpha3`, `iso_numeric`, `currency_code`, `currency_name`, `currency_symbol`, `flag`, `phonecode`) VALUES
(1,	'Afghanistan',	'AF',	'AFG',	4,	'AFN',	'Afghan afghani',	'؋',	'AF.png',	93),
(2,	'Albania',	'AL',	'ALB',	8,	'ALL',	'Albanian Lek',	'ALL',	'AL.png',	355),
(3,	'Algeria',	'DZ',	'DZA',	12,	'DZD',	'Algerian Dinar',	'د.ج',	'DZ.png',	213),
(4,	'American Samoa',	'AS',	'ASM',	16,	'USD',	'United States Dollar',	'$',	'AS.png',	1),
(5,	'Andorra',	'AD',	'AND',	20,	'EUR',	'Euro',	'&euro;',	'AD.png',	376),
(6,	'Angola',	'AO',	'AGO',	24,	'AOA',	'Angolan Kwanza',	'Kz',	'AO.png',	244),
(7,	'Anguilla',	'AI',	'AIA',	660,	'XCD',	'Eastern Caribbean dollar',	'$',	'AI.png',	1),
(8,	'Antarctica',	'AQ',	'AQD',	10,	'A$',	'Antarctican dollar',	'A$',	'AQ.png',	672),
(9,	'Antigua and Barbuda',	'AG',	'ATG',	28,	'XCD',	'Eastern Caribbean dollar',	'$',	'AG.png',	1),
(10,	'Argentina',	'AR',	'ARG',	32,	'ARS',	'Argentine Peso',	'$',	'AR.png',	54),
(11,	'Armenia',	'AM',	'ARM',	51,	'AMD',	'Dram',	'&#1423;',	'AM.png',	374),
(12,	'Aruba',	'AW',	'ABW',	533,	'AWG',	'Guilder',	'&fnof;',	'AW.png',	297),
(13,	'Australia',	'AU',	'AUS',	36,	'AUD',	'Australian dollar',	'$',	'AU.png',	61),
(14,	'Austria',	'AT',	'AUT',	40,	'EUR',	'Euro',	'&euro;',	'AT.png',	43),
(15,	'Azerbaijan',	'AZ',	'AZE',	31,	'AZN',	'Manat',	'&#8380;',	'AZ.png',	994),
(16,	'Bahamas',	'BS',	'BHS',	44,	'BSD',	'Bahamian dollar',	'B$',	'BS.png',	1),
(17,	'Bahrain',	'BH',	'BHR',	48,	'BHD',	'Bahraini dinar',	'.د.ب',	'BH.png',	973),
(18,	'Bangladesh',	'BD',	'BGD',	50,	'BDT',	'Taka',	'&#2547;&nbsp;',	'BD.png',	880),
(19,	'Barbados',	'BB',	'BRB',	52,	'BBD',	'Barbadian dollar',	'Bds$',	'BB.png',	1),
(20,	'Belarus',	'BY',	'BLR',	112,	'BYR',	'Belarusian ruble',	'ꀷ',	'BY.png',	375),
(21,	'Belgium',	'BE',	'BEL',	56,	'EUR',	'Euro',	'&euro;',	'BE.png',	32),
(22,	'Belize',	'BZ',	'BLZ',	84,	'BZD',	'Belize dollar',	'$',	'BZ.png',	501),
(23,	'Benin',	'BJ',	'BEN',	204,	'XOF',	'West African CFA franc',	'Fr',	'BJ.png',	229),
(24,	'Bermuda',	'BM',	'BMU',	60,	'BMD',	'Bermudian dollar',	'$',	'BM.png',	1),
(25,	'Bhutan',	'BT',	'BTN',	64,	'BTN',	'Ngultrum',	'Nu.',	'BT.png',	975),
(26,	'Bolivia',	'BO',	'BOL',	68,	'BOB',	'Bolivian boliviano',	'Bs',	'BO.png',	591),
(27,	'Bosnia and Herzegovina',	'BA',	'BIH',	70,	'BAM',	'Marka',	'KM',	'BA.png',	387),
(28,	'Botswana',	'BW',	'BWA',	72,	'BWP',	'Botswana pula',	'P',	'BW.png',	267),
(29,	'Bouvet Island',	'BV',	'BVT',	74,	'NOK',	'Norwegian Krone',	'kr',	'BV.png',	55),
(30,	'Brazil',	'BR',	'BRA',	76,	'BRL',	'Brazilian real',	'R$',	'BR.png',	55),
(31,	'British Indian Ocean Territory',	'IO',	'IOT',	86,	'USD',	'United States Dollar',	'$',	'IO.png',	246),
(32,	'British Virgin Islands',	'VG',	'VGB',	92,	'USD',	'United States Dollar',	'$',	'VG.png',	1),
(33,	'Brunei',	'BN',	'BRN',	96,	'BND',	'Brunei dollar',	'B$',	'BN.png',	673),
(34,	'Bulgaria',	'BG',	'BGR',	100,	'BGN',	'Lev',	'&#1083;&#1074;.',	'BG.png',	359),
(35,	'Burkina Faso',	'BF',	'BFA',	854,	'XOF',	'West African CFA franc',	'Fr',	'BF.png',	226),
(36,	'Burundi',	'BI',	'BDI',	108,	'BIF',	'Burundian franc',	'Fr',	'BI.png',	257),
(37,	'Cambodia',	'KH',	'KHM',	116,	'KHR',	'Cambodian riel',	'៛',	'KH.png',	855),
(38,	'Cameroon',	'CM',	'CMR',	120,	'XAF',	'Central African CFA franc',	'Fr',	'CM.png',	237),
(39,	'Canada',	'CA',	'CAN',	124,	'CAD',	'Canadian dollar',	'$',	'CA.png',	1),
(40,	'Cape Verde',	'CV',	'CPV',	132,	'CVE',	'Escudo',	'&#36;',	'CV.png',	238),
(41,	'Cayman Islands',	'KY',	'CYM',	136,	'KYD',	'Cayman Islands dollar',	'$',	'KY.png',	1),
(42,	'Central African Republic',	'CF',	'CAF',	140,	'XAF',	'Central African CFA franc',	'Fr',	'CF.png',	236),
(43,	'Chad',	'TD',	'TCD',	148,	'XAF',	'Central African CFA franc',	'Fr',	'TD.png',	235),
(44,	'Chile',	'CL',	'CHL',	152,	'CLP',	'Chilean peso',	'$',	'CL.png',	56),
(45,	'China',	'CN',	'CHN',	156,	'CNY',	'Yuan Renminbi',	'&yen;',	'CN.png',	86),
(46,	'Christmas Island',	'CX',	'CXR',	162,	'AUD',	'Australian dollar',	'$',	'CX.png',	61),
(47,	'Cocos Islands',	'CC',	'CCK',	166,	'AUD',	'Australian dollar',	'$',	'CC.png',	891),
(48,	'Colombia',	'CO',	'COL',	170,	'COP',	'Colombian peso',	'$',	'CO.png',	57),
(49,	'Comoros',	'KM',	'COM',	174,	'KMF',	'Comorian franc',	'Fr',	'KM.png',	269),
(50,	'Cook Islands',	'CK',	'COK',	184,	'NZD',	'New Zealand dollar',	'$',	'CK.png',	670),
(51,	'Costa Rica',	'CR',	'CRI',	188,	'CRC',	'Colon',	'&#x20a1;',	'CR.png',	506),
(52,	'Croatia',	'HR',	'HRV',	191,	'HRK',	'Croatian kuna',	'Kn',	'HR.png',	385),
(53,	'Cuba',	'CU',	'CUB',	192,	'CUP',	'Cuban peso',	'$',	'CU.png',	53),
(54,	'Cyprus',	'CY',	'CYP',	196,	'CYP',	'Cypriot pound',	'£',	'CY.png',	357),
(55,	'Czech Republic',	'CZ',	'CZE',	203,	'CZK',	'Czech koruna',	'Kč',	'CZ.png',	420),
(56,	'Democratic Republic of the Congo',	'CD',	'COD',	180,	'CDF',	'Congolese franc',	'Fr',	'CD.png',	242),
(57,	'Denmark',	'DK',	'DNK',	208,	'DKK',	'Danish krone',	'DKK',	'DK.png',	45),
(58,	'Djibouti',	'DJ',	'DJI',	262,	'DJF',	'Djiboutian franc',	'Fr',	'DJ.png',	253),
(59,	'Dominica',	'DM',	'DMA',	212,	'DOP',	'Dominican peso',	'$',	'DM.png',	1),
(60,	'Dominican Republic',	'DO',	'DOM',	214,	'DOP',	'Dominican peso',	'RD$',	'DO.png',	1),
(61,	'East Timor',	'TL',	'TLS',	626,	'USD',	'United States Dollar',	'$',	'TL.png',	670),
(62,	'Ecuador',	'EC',	'ECU',	218,	'USD',	' United States Dollar',	'$',	'EC.png',	593),
(63,	'Egypt',	'EG',	'EGY',	818,	'EGP',	'Egyptian pound',	'E£',	'EG.png',	20),
(64,	'El Salvador',	'SV',	'SLV',	222,	'SVC',	'Salvadoran colón',	'₡',	'SV.png',	503),
(65,	'Equatorial Guinea',	'GQ',	'GNQ',	226,	'XAF',	'Central African CFA franc',	'Fr',	'GQ.png',	240),
(66,	'Eritrea',	'ER',	'ERI',	232,	'ERN',	'Nakfa',	'Nfk',	'ER.png',	291),
(67,	'Estonia',	'EE',	'EST',	233,	'EEK',	'Estonian kroon',	'kr',	'EE.png',	372),
(68,	'Ethiopia',	'ET',	'ETH',	231,	'ETB',	'Ethiopian birr',	'Br',	'ET.png',	251),
(279,	'Ghana',	'GH',	'GHA',	NULL,	'GHS',	'Ghanaian cedi',	'GH₵',	NULL,	233),
(70,	'Faroe Islands',	'FO',	'FRO',	234,	'DKK',	'Danish krone',	'DKK',	'FO.png',	298),
(71,	'Fiji',	'FJ',	'FJI',	242,	'FJD',	'Fijian dollar',	'$',	'FJ.png',	679),
(72,	'Finland',	'FI',	'FIN',	246,	'EUR',	'Euro',	'&euro;',	'FI.png',	358),
(73,	'France',	'FR',	'FRA',	250,	'EUR',	'Euro',	'&euro;',	'FR.png',	33),
(74,	'French Guiana',	'GF',	'GUF',	254,	'EUR',	'Euro',	'&euro;',	'GF.png',	594),
(75,	'French Polynesia',	'PF',	'PYF',	258,	'XPF',	'CFP franc',	'Fr',	'PF.png',	689),
(76,	'French Southern Territories',	'TF',	'ATF',	260,	'EUR',	'Euro  ',	'€',	'TF.png',	689),
(77,	'Gabon',	'GA',	'GAB',	266,	'XAF',	'Central African CFA franc',	'Fr',	'GA.png',	241),
(78,	'Gambia',	'GM',	'GMB',	270,	'GMD',	'Gambian Dalasi',	'D',	'GM.png',	220),
(79,	'Georgia',	'GE',	'GEO',	268,	'GEL',	'Georgian Lari',	'ლ',	'GE.png',	995),
(80,	'Germany',	'DE',	'DEU',	276,	'EUR',	'Euro',	'&euro;',	'DE.png',	49),
(81,	'Ghana',	'GH',	'GHA',	288,	'GHC',	'Ghanaian cedi',	' GH₵',	'GH.png',	233),
(82,	'Gibraltar',	'GI',	'GIB',	292,	'GIP',	'Gibraltar Pound',	'£',	'GI.png',	350),
(83,	'Greece',	'GR',	'GRC',	300,	'EUR',	'Euro',	'&euro;',	'GR.png',	30),
(84,	'Greenland',	'GL',	'GRL',	304,	'DKK',	'Danish krone',	'DKK',	'GL.png',	299),
(85,	'Grenada',	'GD',	'GRD',	308,	'XCD',	'Eastern Caribbean dollar',	'$',	'GD.png',	1),
(86,	'Guadeloupe',	'GP',	'GLP',	312,	'EUR',	'Euro',	'&euro;',	'GP.png',	590),
(87,	'Guam',	'GU',	'GUM',	316,	'USD',	'United States Dollar',	'$',	'GU.png',	1),
(88,	'Guatemala',	'GT',	'GTM',	320,	'GTQ',	'Guatemala Quetzal',	'Q',	'GT.png',	502),
(89,	'Guinea',	'GN',	'GIN',	324,	'GNF',	'Guinea Franc',	'Fr',	'GN.png',	224),
(90,	'Guinea-Bissau',	'GW',	'GNB',	624,	'XOF',	'West African CFA franc',	'Fr',	'GW.png',	245),
(91,	'Guyana',	'GY',	'GUY',	328,	'GYD',	'Guyana Dollar',	'$',	'GY.png',	592),
(92,	'Haiti',	'HT',	'HTI',	332,	'HTG',	'Haiti Gourde',	'G',	'HT.png',	509),
(93,	'Heard Island and McDonald Islands',	'HM',	'HMD',	334,	'AUD',	'Australian Dollar',	'$',	'HM.png',	672),
(94,	'Honduras',	'HN',	'HND',	340,	'HNL',	'Honduras Lempira',	'L',	'HN.png',	504),
(95,	'Hong Kong',	'HK',	'HKG',	344,	'HKD',	'Hong Kong Dollar',	'$',	'HK.png',	852),
(96,	'Hungary',	'HU',	'HUN',	348,	'HUF',	'Hungary Forint',	'Ft',	'HU.png',	36),
(97,	'Iceland',	'IS',	'ISL',	352,	'ISK',	'Iceland Krona',	'kr.',	'IS.png',	354),
(98,	'India',	'IN',	'IND',	356,	'INR',	'Indian rupee',	'₹',	'IN.png',	91),
(99,	'Indonesia',	'ID',	'IDN',	360,	'IDR',	'Indonesian Rupiah',	'Rp',	'ID.png',	62),
(100,	'Iran',	'IR',	'IRN',	364,	'IRR',	'Iranian Rial',	'﷼',	'IR.png',	98),
(101,	'Iraq',	'IQ',	'IRQ',	368,	'IQD',	'Iraqi Dinar',	'ع.د',	'IQ.png',	964),
(102,	'Ireland',	'IE',	'IRL',	372,	'EUR',	'Euro',	'&euro;',	'IE.png',	353),
(103,	'Israel',	'IL',	'ISR',	376,	'ILS',	'Israeli new Shekel',	'₪',	'IL.png',	972),
(104,	'Italy',	'IT',	'ITA',	380,	'EUR',	'Euro',	'&euro;',	'IT.png',	39),
(105,	'Ivory Coast',	'CI',	'CIV',	384,	'XOF',	'West African CFA franc',	'Fr',	'CI.png',	225),
(106,	'Jamaica',	'JM',	'JAM',	388,	'JMD',	'Jamaican Dollar',	'$',	'JM.png',	1),
(107,	'Japan',	'JP',	'JPN',	392,	'JPY',	'Japanese Yen',	'¥',	'JP.png',	81),
(108,	'Jordan',	'JO',	'JOR',	400,	'JOD',	'Jordanian Dinar',	'د.ا',	'JO.png',	962),
(109,	'Kazakhstan',	'KZ',	'KAZ',	398,	'KZT',	'Kazakhstani Tenge',	'KZT',	'KZ.png',	7),
(110,	'Kenya',	'KE',	'KEN',	404,	'KES',	'Kenyan Shilling',	'KSh',	'KE.png',	254),
(111,	'Kiribati',	'KI',	'KIR',	296,	'AUD',	' Australian dollar',	'$',	'KI.png',	686),
(112,	'Kuwait',	'KW',	'KWT',	414,	'KWD',	'Kuwaiti Dinar',	'د.ك',	'KW.png',	965),
(113,	'Kyrgyzstan',	'KG',	'KGZ',	417,	'KGS',	'Kyrgyzstani Som',	'сом',	'KG.png',	996),
(114,	'Laos',	'LA',	'LAO',	418,	'LAK',	'Lao Kip',	'₭',	'LA.png',	856),
(115,	'Latvia',	'LV',	'LVA',	428,	'LVL',	'Latvian Lats',	'Ls',	'LV.png',	371),
(116,	'Lebanon',	'LB',	'LBN',	422,	'LBP',	'Lebanese Pound',	'ل.ل',	'LB.png',	961),
(117,	'Lesotho',	'LS',	'LSO',	426,	'LSL',	'Lesotho Loti',	'L',	'LS.png',	266),
(118,	'Liberia',	'LR',	'LBR',	430,	'LRD',	'Liberian Dollar',	'$',	'LR.png',	231),
(119,	'Libya',	'LY',	'LBY',	434,	'LYD',	'Libyan Dinar',	'ل.د',	'LY.png',	218),
(120,	'Liechtenstein',	'LI',	'LIE',	438,	'CHF',	'Swiss franc',	'CHF',	'LI.png',	423),
(121,	'Lithuania',	'LT',	'LTU',	440,	'LTL',	'Lithuanian Litas',	'Lt',	'LT.png',	370),
(122,	'Luxembourg',	'LU',	'LUX',	442,	'EUR',	'Euro',	'&euro;',	'LU.png',	352),
(123,	'Macao',	'MO',	'MAC',	446,	'MOP',	'Macanese Pataca',	'MOP$',	'MO.png',	853),
(124,	'Macedonia',	'MK',	'MKD',	807,	'MKD',	'Macedonian Denar',	'ден',	'MK.png',	389),
(125,	'Madagascar',	'MG',	'MDG',	450,	'MGA',	'Malagasy ariary',	'Ar',	'MG.png',	261),
(126,	'Malawi',	'MW',	'MWI',	454,	'MWK',	'Malawian Kwacha',	'MK',	'MW.png',	265),
(127,	'Malaysia',	'MY',	'MYS',	458,	'MYR',	'Malaysian Ringgit',	'RM',	'MY.png',	60),
(128,	'Maldives',	'MV',	'MDV',	462,	'MVR',	'Maldivian Rufiyaa',	'.ރ',	'MV.png',	960),
(129,	'Mali',	'ML',	'MLI',	466,	'XOF',	'West African CFA franc',	'Fr',	'ML.png',	223),
(130,	'Malta',	'MT',	'MLT',	470,	'MTL',	'Maltese lira',	'₤ ',	'MT.png',	356),
(131,	'Marshall Islands',	'MH',	'MHL',	584,	'USD',	' United States Dollar',	'$',	'MH.png',	692),
(132,	'Martinique',	'MQ',	'MTQ',	474,	'EUR',	'Euro',	'&euro;',	'MQ.png',	596),
(133,	'Mauritania',	'MR',	'MRT',	478,	'MRO',	'Mauritanian Ouguiya',	'UM',	'MR.png',	222),
(134,	'Mauritius',	'MU',	'MUS',	480,	'MUR',	'Mauritian Rupee',	'₨',	'MU.png',	230),
(135,	'Mayotte',	'YT',	'MYT',	175,	'EUR',	'Euro',	'€',	'YT.png',	262),
(136,	'Mexico',	'MX',	'MEX',	484,	'MXN',	'Mexican Peso',	'$',	'MX.png',	52),
(137,	'Micronesia',	'FM',	'FSM',	583,	'USD',	' United States Dollar',	'$',	'FM.png',	691),
(138,	'Moldova',	'MD',	'MDA',	498,	'MDL',	'Moldovan Leu',	'L',	'MD.png',	373),
(139,	'Monaco',	'MC',	'MCO',	492,	'EUR',	'Euro',	'&euro;',	'MC.png',	377),
(140,	'Mongolia',	'MN',	'MNG',	496,	'MNT',	'Mongolian Tugrik',	'₮',	'MN.png',	976),
(141,	'Montserrat',	'MS',	'MSR',	500,	'XCD',	' Eastern Caribbean dollar',	'$',	'MS.png',	1),
(142,	'Morocco',	'MA',	'MAR',	504,	'MAD',	'Moroccan Dirham',	'د.م.',	'MA.png',	212),
(143,	'Mozambique',	'MZ',	'MOZ',	508,	'MZN',	'Meticail',	'MT',	'MZ.png',	258),
(144,	'Myanmar',	'MM',	'MMR',	104,	'MMK',	'Burmese kyat',	'K',	'MM.png',	95),
(145,	'Namibia',	'NA',	'NAM',	516,	'NAD',	'Namibian Dollar',	'$',	'NA.png',	264),
(146,	'Nauru',	'NR',	'NRU',	520,	'AUD',	' Australian dollar',	'$',	'NR.png',	674),
(147,	'Nepal',	'NP',	'NPL',	524,	'NPR',	'Nepalese Rupee',	'₨',	'NP.png',	977),
(148,	'Netherlands',	'NL',	'NLD',	528,	'EUR',	'Euro',	'&euro;',	'NL.png',	31),
(149,	'Netherlands Antilles',	'AN',	'ANT',	530,	'ANG',	'Netherlands Antillean Guilder',	'ƒ',	'AN.png',	599),
(150,	'New Caledonia',	'NC',	'NCL',	540,	'XPF',	'CFP franc',	'Fr',	'NC.png',	687),
(151,	'New Zealand',	'NZ',	'NZL',	554,	'NZD',	'New Zealand Dollar',	'$',	'NZ.png',	64),
(152,	'Nicaragua',	'NI',	'NIC',	558,	'NIO',	'Nicaraguan Cordoba',	'C$',	'NI.png',	505),
(153,	'Niger',	'NE',	'NER',	562,	'XOF',	'West African CFA franc',	'Fr',	'NE.png',	227),
(154,	'Nigeria',	'NG',	'NGA',	566,	'NGN',	'Nigerian Naira',	'₦',	'NG.png',	234),
(155,	'Niue',	'NU',	'NIU',	570,	'NZD',	'New Zealand dollar',	'$',	'NU.png',	683),
(156,	'Norfolk Island',	'NF',	'NFK',	574,	'AUD',	'Australian dollar',	'$',	'NF.png',	672),
(157,	'North Korea',	'KP',	'PRK',	408,	'KPW',	'North Korean Won',	'₩',	'KP.png',	850),
(158,	'Northern Mariana Islands',	'MP',	'MNP',	580,	'USD',	' United States Dollar',	'$',	'MP.png',	1),
(159,	'Norway',	'NO',	'NOR',	578,	'NOK',	'Norwegian Krone',	'kr',	'NO.png',	47),
(160,	'Oman',	'OM',	'OMN',	512,	'OMR',	'Omani Rial',	'ر.ع.',	'OM.png',	968),
(161,	'Pakistan',	'PK',	'PAK',	586,	'PKR',	'Pakistani Rupee',	'₨',	'PK.png',	92),
(162,	'Palau',	'PW',	'PLW',	585,	'USD',	' United States Dollar',	'$',	'PW.png',	680),
(163,	'Palestinian Territory',	'PS',	'PSE',	275,	'ILS',	'Shekel',	'₪',	'PS.png',	970),
(164,	'Panama',	'PA',	'PAN',	591,	'PAB',	'Panamanian Balboa',	'B/.',	'PA.png',	507),
(165,	'Papua New Guinea',	'PG',	'PNG',	598,	'PGK',	'Papua New Guinean Kina',	'K',	'PG.png',	675),
(166,	'Paraguay',	'PY',	'PRY',	600,	'PYG',	'Paraguayan Guarani',	'₲',	'PY.png',	595),
(167,	'Peru',	'PE',	'PER',	604,	'PEN',	'Sol',	'S/.',	'PE.png',	51),
(168,	'Philippines',	'PH',	'PHL',	608,	'PHP',	'Philippine Peso',	'₱',	'PH.png',	63),
(169,	'Pitcairn',	'PN',	'PCN',	612,	'NZD',	'New Zealand dollar',	'$',	'PN.png',	64),
(170,	'Poland',	'PL',	'POL',	616,	'PLN',	'Polish Zloty',	'zł',	'PL.png',	48),
(171,	'Portugal',	'PT',	'PRT',	620,	'EUR',	'Euro',	'&euro;',	'PT.png',	351),
(172,	'Puerto Rico',	'PR',	'PRI',	630,	'USD',	' United States Dollar',	'$',	'PR.png',	1),
(173,	'Qatar',	'QA',	'QAT',	634,	'QAR',	'Qatari Rial',	'ر.ق',	'QA.png',	974),
(174,	'Republic of the Congo',	'CG',	'COG',	178,	'XAF',	'Central African CFA franc',	'Fr',	'CG.png',	242),
(175,	'Reunion',	'RE',	'REU',	638,	'EUR',	'Euro',	'&euro;',	'RE.png',	262),
(176,	'Romania',	'RO',	'ROU',	642,	'RON',	'Romanian Leu',	'lei',	'RO.png',	40),
(177,	'Russia',	'RU',	'RUS',	643,	'RUB',	'Russian Ruble',	'₽',	'RU.png',	70),
(178,	'Rwanda',	'RW',	'RWA',	646,	'RWF',	'Rwandan Franc',	'Fr',	'RW.png',	250),
(179,	'Saint Helena',	'SH',	'SHN',	654,	'SHP',	'Saint Helena Pound',	'£',	'SH.png',	290),
(180,	'Saint Kitts and Nevis',	'KN',	'KNA',	659,	'XCD',	' Eastern Caribbean dollar',	'$',	'KN.png',	1),
(181,	'Saint Lucia',	'LC',	'LCA',	662,	'XCD',	'Eastern Caribbean dollar',	'$',	'LC.png',	1),
(182,	'Saint Pierre and Miquelon',	'PM',	'SPM',	666,	'EUR',	'Euro',	'&euro;',	'PM.png',	508),
(183,	'Saint Vincent and the Grenadines',	'VC',	'VCT',	670,	'XCD',	'Eastern Caribbean dollar',	'$',	'VC.png',	1),
(184,	'Samoa',	'WS',	'WSM',	882,	'WST',	'Samoan Tala',	'T',	'WS.png',	684),
(185,	'San Marino',	'SM',	'SMR',	674,	'EUR',	'Euro',	'&euro;',	'SM.png',	378),
(186,	'Sao Tome and Principe',	'ST',	'STP',	678,	'STD',	'Sao Tome and Principe Dobra',	'Db',	'ST.png',	239),
(187,	'Saudi Arabia',	'SA',	'SAU',	682,	'SAR',	'Saudi Arabian Rial',	'ر.س',	'SA.png',	966),
(188,	'Senegal',	'SN',	'SEN',	686,	'XOF',	'West African CFA franc',	'Fr',	'SN.png',	221),
(189,	'Serbia and Montenegro',	'CS',	'SCG',	891,	'RSD',	'SerbianDinar',	'дин.',	'CS.png',	381),
(190,	'Seychelles',	'SC',	'SYC',	690,	'SCR',	'Seychellois Rupee',	'₨',	'SC.png',	248),
(191,	'Sierra Leone',	'SL',	'SLE',	694,	'SLL',	'Sierra Leonean Leone',	'Le',	'SL.png',	232),
(192,	'Singapore',	'SG',	'SGP',	702,	'SGD',	'Singapore Dollar',	'$',	'SG.png',	65),
(193,	'Slovakia',	'SK',	'SVK',	703,	'SKK',	'Slovak Koruna',	'Sk',	'SK.png',	421),
(194,	'Slovenia',	'SI',	'SVN',	705,	'EUR',	'Euro',	'&euro;',	'SI.png',	386),
(195,	'Solomon Islands',	'SB',	'SLB',	90,	'SBD',	'Solomon Islands Dollar',	'$',	'SB.png',	677),
(196,	'Somalia',	'SO',	'SOM',	706,	'SOS',	'Somalian Shilling',	'Sh',	'SO.png',	252),
(197,	'South Africa',	'ZA',	'ZAF',	710,	'ZAR',	'South African Rand',	'R',	'ZA.png',	27),
(198,	'South Georgia and the South Sandwich Islands',	'GS',	'SGS',	239,	'GBP',	'Pound sterling',	'£',	'GS.png',	500),
(199,	'South Korea',	'KR',	'KOR',	410,	'KRW',	'South Korean Won',	'₩',	'KR.png',	82),
(200,	'Spain',	'ES',	'ESP',	724,	'EUR',	'Euro',	'&euro;',	'ES.png',	34),
(201,	'Sri Lanka',	'LK',	'LKA',	144,	'LKR',	'Sri Lankan Rupee',	'රු',	'LK.png',	94),
(202,	'Sudan',	'SD',	'SDN',	736,	'SDD',	'Sudanese pound',	' ج.س',	'SD.png',	249),
(203,	'Suriname',	'SR',	'SUR',	740,	'SRD',	'Surinamese Dollar',	'$',	'SR.png',	597),
(204,	'Svalbard and Jan Mayen',	'SJ',	'SJM',	744,	'NOK',	'Svalbard and Jan Mayen Krone',	'kr',	'SJ.png',	47),
(205,	'Swaziland',	'SZ',	'SWZ',	748,	'SZL',	'Swazi lilangeni',	'L',	'SZ.png',	268),
(206,	'Sweden',	'SE',	'SWE',	752,	'SEK',	'Swedish krona',	'kr',	'SE.png',	46),
(207,	'Switzerland',	'CH',	'CHE',	756,	'CHF',	'Swiss franc',	'CHF',	'CH.png',	41),
(208,	'Syria',	'SY',	'SYR',	760,	'SYP',	'Syrian Pound',	'ل.س',	'SY.png',	963),
(209,	'Taiwan',	'TW',	'TWN',	158,	'TWD',	'New Taiwan dollar',	'NT$',	'TW.png',	886),
(210,	'Tajikistan',	'TJ',	'TJK',	762,	'TJS',	'Tajikistani Somoni',	'ЅМ',	'TJ.png',	992),
(211,	'Tanzania',	'TZ',	'TZA',	834,	'TZS',	'Tanzanian Shilling',	'Sh',	'TZ.png',	255),
(212,	'Thailand',	'TH',	'THA',	764,	'THB',	'Thai Bhat',	'฿',	'TH.png',	66),
(213,	'Togo',	'TG',	'TGO',	768,	'XOF',	'West African CFA franc',	'Fr',	'TG.png',	228),
(214,	'Tokelau',	'TK',	'TKL',	772,	'NZD',	'New Zealand dollar',	'$',	'TK.png',	690),
(215,	'Tonga',	'TO',	'TON',	776,	'TOP',	'Tongan Pa&#39;anga',	'T$',	'TO.png',	676),
(216,	'Trinidad and Tobago',	'TT',	'TTO',	780,	'TTD',	'Trinidad and Tobago Dollar',	'$',	'TT.png',	1),
(217,	'Tunisia',	'TN',	'TUN',	788,	'TND',	'Tunisian Dinar',	'د.ت',	'TN.png',	216),
(218,	'Turkey',	'TR',	'TUR',	792,	'TRY',	'Turkish Lira',	'₺',	'TR.png',	90),
(219,	'Turkmenistan',	'TM',	'TKM',	795,	'TMM',	'Turkmenistan manat',	'T',	'TM.png',	7370),
(220,	'Turks and Caicos Islands',	'TC',	'TCA',	796,	'USD',	' United States Dollar',	'$',	'TC.png',	1),
(221,	'Tuvalu',	'TV',	'TUV',	798,	'AUD',	'Australian dollar',	'$',	'TV.png',	688),
(222,	'U.S. Virgin Islands',	'VI',	'VIR',	850,	'USD',	' United States Dollar',	'$',	'VI.png',	1),
(223,	'Uganda',	'UG',	'UGA',	800,	'UGX',	'Ugandan Shilling',	'UGX',	'UG.png',	256),
(224,	'Ukraine',	'UA',	'UKR',	804,	'UAH',	'Ukrainian Hryvnia',	'₴',	'UA.png',	380),
(225,	'United Arab Emirates',	'AE',	'ARE',	784,	'AED',	'United Arab Emirates Dirham',	'د.إ',	'AE.png',	971),
(226,	'United Kingdom',	'GB',	'GBR',	826,	'GBP',	'Pound sterling',	'£',	'GB.png',	44),
(227,	'United States',	'US',	'USA',	840,	'USD',	'United States Dollar',	'$',	'US.png',	1),
(228,	'United States Minor Outlying Islands',	'UM',	'UMI',	581,	'USD',	'United States Dollar',	'$',	'UM.png',	1),
(229,	'Uruguay',	'UY',	'URY',	858,	'UYU',	'Uruguayan Peso',	'$',	'UY.png',	598),
(230,	'Uzbekistan',	'UZ',	'UZB',	860,	'UZS',	'Uzbekistani Som',	'UZS',	'UZ.png',	998),
(231,	'Vanuatu',	'VU',	'VUT',	548,	'VUV',	'Vanuatu vatu',	'Vt',	'VU.png',	678),
(232,	'Vatican',	'VA',	'VAT',	336,	'EUR',	'Euro',	'&euro;',	'VA.png',	39),
(233,	'Venezuela',	'VE',	'VEN',	862,	'VEF',	'Venezuelan Bolivar',	'Bs F',	'VE.png',	58),
(234,	'Vietnam',	'VN',	'VNM',	704,	'VND',	'Vietnamese Dong',	'₫',	'VN.png',	84),
(235,	'Wallis and Futuna',	'WF',	'WLF',	876,	'XPF',	'CFP franc',	'Fr',	'WF.png',	681),
(236,	'Western Sahara',	'EH',	'ESH',	732,	'MAD',	'Dirham',	'&#x62f;.&#x645;.',	'EH.png',	212),
(237,	'Yemen',	'YE',	'YEM',	887,	'YER',	'Yemeni Rial',	'﷼',	'YE.png',	967),
(238,	'Zambia',	'ZM',	'ZMB',	894,	'ZMK',	'Zambian Kwacha',	'ZK',	'ZM.png',	260),
(239,	'Zimbabwe',	'ZW',	'ZWE',	716,	'ZWD',	'Zimbabwean dollar',	'$',	'ZW.png',	263),
(242,	'British Pound',	'GB',	'GBP',	NULL,	'GBP',	'British Pound',	'GBP',	NULL,	44),
(247,	'Bangladesh',	'BD',	'BGD',	NULL,	'BDT',	'Bangladeshi taka',	'৳',	NULL,	880),
(244,	'Armenia',	'AM',	'ARM',	NULL,	'AMD',	'Armenian Dram',	'֏ ',	NULL,	374),
(245,	'Aruba',	'AW',	'ABW',	NULL,	'AWG',	'Aruban florin',	'Afl',	NULL,	297),
(246,	'Azerbaijan',	'AZ',	'AZE',	NULL,	'AZN',	'Azerbaijani manat',	'₼',	NULL,	994),
(248,	'Belarus',	'BY',	'BLR',	NULL,	'BYN',	'Belarusian ruble',	'p',	NULL,	375),
(249,	'Bhutan',	'BT',	'BTN',	NULL,	'BTN',	'Bhutanese ngultrum',	'Nu',	NULL,	975),
(250,	'Bosnia',	'BA',	'BIH',	NULL,	'BAM',	'Bosnia and Herzegovina convertib',	'KM',	NULL,	387),
(251,	'Botswana',	'BW',	'BWA',	NULL,	'BWP',	'Botswana pula',	'P',	NULL,	267),
(252,	'Brazil',	'BR',	'BRA',	NULL,	'BRL',	'Brazilian real',	'R$',	NULL,	55),
(253,	'Bulgaria',	'BG',	'BGR',	NULL,	'BGN',	'Bulgarian lev',	'лв',	NULL,	359),
(254,	'Burundi',	'BI',	'BDI',	NULL,	'BIF',	'Burundian franc',	'FBu',	NULL,	257),
(255,	'Cambodia',	'CM',	'CMR',	NULL,	'KHR',	'Cambodian riel',	'៛',	NULL,	855),
(256,	'Cape Verde',	'CV',	'CPV',	NULL,	'CVE',	'Cape Verdean escudo',	' $',	NULL,	238),
(257,	'Central African Republic',	'CF',	'CAF',	NULL,	'XAF',	'Central African CFA franc',	'FCFA',	NULL,	236),
(258,	'French Polynesia',	'PF',	'PYF',	NULL,	'XPF',	'CFP franc',	'CFP',	NULL,	689),
(259,	'Chile',	'CL',	'CHL',	NULL,	'CLP',	'Chilean peso',	' $',	NULL,	56),
(261,	'China',	'CN',	'CHN',	NULL,	'CNY',	'Renminbi',	'元',	NULL,	86),
(262,	'China',	'CN',	'CHN',	NULL,	'CNY',	'Renminbi',	'元',	NULL,	86),
(263,	'Colombia',	'CO',	'COL',	NULL,	'COP',	'Colombian peso',	'$',	NULL,	57),
(264,	'Comoros',	'KM',	'COM',	NULL,	'KMF',	'Comorian franc',	'CF',	NULL,	269),
(265,	'Democratic Republic of the Congo',	'CG',	'COG',	NULL,	'CDF',	'Congolese franc',	'FC',	NULL,	242),
(266,	'Costa Rica',	'CR',	'CRI',	NULL,	'CRC',	'Costa Rican colón',	'₡',	NULL,	506),
(267,	'Croatia',	'HR',	'HRV',	NULL,	'HRK',	'Croatian kuna',	'kn',	NULL,	385),
(268,	'Cuba',	'CU',	'CUB',	NULL,	'CUC',	'Cuban convertible peso',	'$',	NULL,	53),
(269,	'Denmark',	'DK',	'DNK',	NULL,	'DKK',	'Danish krone',	'kr',	NULL,	45),
(270,	'Djibouti',	'DJ',	'DJI',	NULL,	'DJF',	'Djiboutian franc',	'Fdj',	NULL,	253),
(272,	'Eritrea',	'ER',	'ERI',	NULL,	'ERN',	'Eritrean nakfa',	'ናቕፋ',	NULL,	291),
(273,	'Estonia',	'EE',	'EST',	NULL,	'EEK',	'Estonian kroon',	'kr',	NULL,	372),
(275,	'Austria',	'AT',	'AUT',	NULL,	'EUR',	'Euro',	'€',	NULL,	43),
(276,	'Falkland Islands',	'FK',	'FLK',	NULL,	'FKP',	'Falkland Islands pound',	'£',	NULL,	500),
(280,	'Itali',	'IT',	'ITA',	NULL,	'ITL',	'Italian lira',	'₤',	NULL,	39),
(281,	'Mozambique',	'MZ',	'MOZ',	NULL,	'MZN',	'Mozambican metical',	'MT',	NULL,	258),
(282,	'Sudan',	'SD',	'SDN',	NULL,	'SDG',	'Sudanese pound',	'SD',	NULL,	249),
(283,	'Turkmenistan',	'TM',	'TMT',	NULL,	'TMT',	'Turkmenistan manat',	'T',	NULL,	993),
(284,	'Vanuatu',	'VU',	'VUT',	NULL,	'VUV',	'Vanuatu vatu',	'VT',	NULL,	678);";

	if ( version_compare( $previous_version, $db_version_countries, '<' ) ) {
		$query = "DELETE FROM $kg_countries";
		$wpdb->get_results( $query );
	}
	$query = "SELECT * FROM $kg_countries";
    $countryData = $wpdb->get_results( $query );
	
	if ( count( $countryData ) == 0 ) {
		$wpdb->query( $kg_countries_data );
	}
}

function knowing_god_install_pages() {
	global $user_ID;
	if ( get_page_by_title( 'Sign In' ) == NULL) {
		$new_post = array(
			'post_title' => 'Sign In',
			'post_content' => '[knowing_god_signin]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}

	if ( get_page_by_title( 'Registration' ) == NULL) {
		$new_post = array(
			'post_title' => 'Registration',
			'post_content' => '[knowing_god_registration]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}

	if ( get_page_by_title( 'forgotpassword' ) == NULL) {
		$new_post = array(
			'post_title' => 'forgotpassword',
			'post_content' => '[knowing_god_forgotpassword]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}

	if ( get_page_by_title( 'resetpassword' ) == NULL) {
		$new_post = array(
			'post_title' => 'resetpassword',
			'post_content' => '[knowing_god_resetpassword]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}

	if ( get_page_by_title( 'User Account' ) == NULL) {
		$new_post = array(
			'post_title' => 'User Account',
			'post_content' => '[knowing_god_user_account]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post );
		if ( $post_id > 0 ) {
			/**
			 * Let us add OR update 'sidebar_position' meta filed
			 */
			if ( ! add_post_meta( $post_id, 'sidebar_position', 'none', true ) ) {
			   update_post_meta( $post_id, 'sidebar_position', 'none' );
			}
			
			/**
			 * Let us add OR update 'page_title' meta filed
			 */
			if ( ! add_post_meta( $post_id, 'page_title', 'hide', true ) ) {
			   update_post_meta( $post_id, 'page_title', 'hide	' );
			}
			
			/**
			 * Let us add OR update 'page_banner' meta filed
			 */
			if ( ! add_post_meta( $post_id, 'page_banner', 'hide', true ) ) {
			   update_post_meta( $post_id, 'page_banner', 'hide	' );
			}
		}
	}
	
	if ( get_page_by_title( 'WP Laravel Sync' ) == NULL) {
		$new_post = array(
			'post_title' => 'WP Laravel Sync',
			'post_content' => '[knowing_god_wp_sync]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}
	
	if ( get_page_by_title( 'WP Logout' ) == NULL) {
		$new_post = array(
			'post_title' => 'WP Logout',
			'post_content' => '[knowing_god_wp_logout]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}
	
	if ( get_page_by_title( 'WP Login Redirect' ) == NULL) {
		$new_post = array(
			'post_title' => 'WP Login Redirect',
			'post_content' => '[knowing_god_wp_login_redirect]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}
	
	if ( get_page_by_title( 'Sync Laravel Users' ) == NULL) {
		$new_post = array(
			'post_title' => 'Sync Laravel Users',
			'post_content' => '[knowing_god_sync_laravel_users]',
			'post_status' => 'publish',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_author' => $user_ID,
			'post_type' => 'page',
		);
		$post_id = wp_insert_post( $new_post);
	}
}

/**
 * Common function to check wether sms/email template exists.
 * @param  string $name [template name]
 * @return [bool]       [exists true]
 */
function knowing_god_template_is_exists( $name='' )
{
	if( $name!='' )
	{
		global $wpdb;
		$name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title='$name'");
		if( $name == NULL) return FALSE;
		else return TRUE;
	}
	else return FALSE;
}

function knowing_god_default_templates() {
	global $user_ID;
	if ( ! knowing_god_template_is_exists( 'new-user' ) ) {

	   $new_user = array(
	  'post_title'    => wp_strip_all_tags( 'new-user' ),
	  'post_content'  => '&nbsp;
							<h1>{FIRST_NAME}</h1>
							<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1>{BLOG_TITLE}</h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
							</div>
							<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Congratulations..! welcome to {BLOG_TITLE}</strong></span></center>
							<p style="text-align: center;"><span style="color: #333333;">Thank you for For Registering  with {BLOG_TITLE}</span></p>
							<p style="text-align: center;"><span style="color: #333333;">Please login to book journy with us..</span></p>
							<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
							</div>
							<h1>{FIRST_NAME}</h1>
							&nbsp;',
	  'post_status'   => 'publish',
	  'post_author'   => $user_ID,
	  'post_type' => 'emailtemplate'
		);
		wp_insert_post( $new_user);
	}

	if ( ! knowing_god_template_is_exists( 'resetpassword-mail' ) ) {

	   $new_user = array(
	  'post_title'    => wp_strip_all_tags( 'resetpassword-mail' ),
	  'post_content'  => '<div class="mailer" style="width: 800px; border-top: 3px solid #71b4ff;">
							<div class="header" style="background: #f5f5f5; padding: 20px;">
							<h1>{BLOG_TITLE}</h1>
							<div class="btn" style="background: #fff none repeat scroll 0 0; border-radius: 100px; color: #235072; float: right; font-weight: 900; padding: 5px 25px;"><a style="color: #235072; text-decoration: none;" href="#"> {DATE}</a></div>
							</div>
							<div class="content" style="padding: 20px;"><center><span style="color: #00ccff;"><strong>Welcome to {BLOG_TITLE}</strong></span></center>
							<p style="text-align: center;"><span style="color: #333333;">Someone requested that the password be reset for the following account on {BLOG_TITLE}</span></p>
							<p>	{BLOG_LINK} </p>
							<p style="text-align: center;"><span style="color: #333333;">Username or Email</span> {USER_NAME}</p>
							<p style="text-align: center;"><span style="color: #333333;">If this was a mistake, just ignore this email and nothing will happen.</span></p>

							<p style="text-align: center;"><span style="color: #333333;">To reset your password, visit the following address:</span></p>

							<p>{RESET_LINK}</p>

							<p style="text-align: center;"><span style="color: #333333;">Thanks</span></p>

							</div>
							<div class="footer" style="background: #253951; padding: 20px; color: #fff; font-size: 13px;"><span style="float: right;">Copyright © 2016 {BLOG_TITLE} . All right reserved Inc. </span></div>
							</div>',
	  'post_status'   => 'publish',
	  'post_author'   => $user_ID,
	  'post_type' => 'emailtemplate'
		);
		wp_insert_post( $new_user);
	}
}
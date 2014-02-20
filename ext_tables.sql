#
# Table structure for table 'tx_happyfeet_domain_model_footnote'
#
CREATE TABLE tx_happyfeet_domain_model_footnote (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	index int(11) NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	header varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,
);
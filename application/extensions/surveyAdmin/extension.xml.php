<?xml version="1.0" encoding="utf-8" ?>
<!-- <?php exit(); ?> -->
<scabbia>
	<info>
		<name>surveyAdmin</name>
		<version>1.0.2</version>
		<license>GPLv3</license>
		<phpversion>5.2.0</phpversion>
		<phpdependList />
		<fwversion>1.0</fwversion>
		<fwdependList>
			<fwdepend>string</fwdepend>
			<fwdepend>resources</fwdepend>
			<fwdepend>validation</fwdepend>
			<fwdepend>http</fwdepend>
			<fwdepend>auth</fwdepend>
			<fwdepend>zmodels</fwdepend>
		</fwdependList>
	</info>
	<includeList>
		<include>admin_questions.php</include>
		<include>admin_languages.php</include>
		<include>admin_themes.php</include>
		<include>admin_categories.php</include>
		<include>admin_surveys.php</include>
	</includeList>
	<classList>
		<class>admin_questions</class>
		<class>admin_languages</class>
		<class>admin_themes</class>
		<class>admin_categories</class>
		<class>admin_surveys</class>
	</classList>
	<eventList>
		<event>
			<name>blackmore_registerModules</name>
			<callback>admin_questions::blackmore_registerModules</callback>
		</event>
		<event>
			<name>blackmore_registerModules</name>
			<callback>admin_languages::blackmore_registerModules</callback>
		</event>
		<event>
			<name>blackmore_registerModules</name>
			<callback>admin_themes::blackmore_registerModules</callback>
		</event>
		<event>
			<name>blackmore_registerModules</name>
			<callback>admin_categories::blackmore_registerModules</callback>
		</event>
		<event>
			<name>blackmore_registerModules</name>
			<callback>admin_surveys::blackmore_registerModules</callback>
		</event>
	</eventList>
</scabbia>

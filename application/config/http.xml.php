<?xml version="1.0" encoding="utf-8" ?>
<!-- <?php exit(); ?> -->
<scabbia>
	<http>
		<request>
			<parsingType>2</parsingType>
			<getParameters>?&amp;,</getParameters>
			<getKeys>=:</getKeys>
			<getSeperator>/</getSeperator>
		</request>

		<rewriteList>
			<rewrite>
				<match>regions/([a-z_]+)/([a-z_]+)/([a-z0-9\-]+)</match>
				<forward>firms/$3</forward>
			</rewrite>
		</rewriteList>
	</http>

	<session>
		<cookie>
			<name>sessid</name>
			<life>0</life>
			<ipCheck>0</ipCheck>
			<uaCheck>1</uaCheck>
		</cookie>
	</session>

	<access>
		<maintenance>
			<mode>0</mode>
			<page>{app}views/static_maintenance.php</page>
			<mvcpage>shared/maintenance.cshtml</mvcpage>
			<ipExcludeList>
				<ipExclude>127.0.0.1</ipExclude>
			</ipExcludeList>
		</maintenance>

		<ipFilter>
			<page>{app}views/static_ipban.php</page>
			<mvcpage>shared/ipban.cshtml</mvcpage>
			<ipFilterList>
				<!--
				<ipFilter>
					<type>deny</type>
					<pattern>127.0.0.?</pattern>
				</ipFilter>
				-->
				<ipFilter>
					<type>allow</type>
					<pattern>*.*.*.*</pattern>
				</ipFilter>
			</ipFilterList>
		</ipFilter>
	</access>
</scabbia>
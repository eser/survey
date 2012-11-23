<?xml version="1.0" encoding="utf-8" ?>
<!-- <?php exit(); ?> -->
<scabbia>
	<options>
		<gzip>1</gzip>
		<!-- <siteroot>/survey</siteroot> -->
	</options>

	<downloadList />

	<includeList>
		<include>{app}includes/*.php</include>
		<include>{app}writable/downloaded/*.php</include>
		<include>{app}controllers/*.php</include>
		<include>{app}models/*.php</include>
	</includeList>

	<extensionList>
		<extension>database</extension>
		<extension>access</extension>
		<extension>captcha</extension>
	</extensionList>

	<i8n>
		<languageList>
			<language>
				<id>tr</id>
				<locale>tr_TR.UTF-8</locale>
				<localewin>Turkish_Turkey.1254</localewin>
				<internalEncoding>UTF-8</internalEncoding>
				<name>Turkish</name>
			</language>
			<language>
				<id>en</id>
				<locale>en_US.UTF-8</locale>
				<localewin>English_United States.1252</localewin>
				<internalEncoding>UTF-8</internalEncoding>
				<name>English</name>
			</language>
		</languageList>
	</i8n>

	<logger>
		<filename>{date|'d-m-Y'} {@category}.txt</filename>
		<line>[{date|'d-m-Y H:i:s'}] {strtoupper|@category} | {@ip} | {@message}</line>
	</logger>

	<cache>
		<keyphase></keyphase>
		<storage>memcache://192.168.2.4:11211</storage>
	</cache>
</scabbia>
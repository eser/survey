<?xml version="1.0" encoding="utf-8" ?>
<!-- <?php exit(); ?> -->
<scabbia>
	<blackmore>
		<title>Goldsoft: kibrissiparis.com</title>
		<logo>/res/images/logocube.png</logo>
		<bodyStyle>stretch</bodyStyle>

		<loginTitle>Goldsoft: Kibrissiparis Login</loginTitle>
		<database>blackmore</database>
	</blackmore>

	<auth>
		<loginMvcUrl>blackmore/login</loginMvcUrl>
		<authType>config</authType>

		<userList>
			<user>
				<username>admin</username>
				<password>21232f297a57a5a743894a0e4a801fc3</password>
				<roles>admin,editor,user</roles>
			</user>
		</userList>
	</auth>

	<zmodelList>
		<zmodel>
			<name>categories</name>
			<title>Categories</title>
			<singularTitle>Category</singularTitle>
			<fieldList>
				<field>
					<name>categoryid</name>
					<type>uuid</type>
					<title>Id</title>
					<align>left</align>
					<methods>
						<add />
						<view />
						<remove />
					</methods>
					<primaryKey />
				</field>
				<field>
					<name>type</name>
					<type>enum</type>
					<valueList>
						<value>
							<name>post</name>
							<title>Post</title>
						</value>
						<value>
							<name>page</name>
							<title>Page</title>
						</value>
						<value>
							<name>link</name>
							<title>Link</title>
						</value>
						<value>
							<name>file</name>
							<title>File</title>
						</value>
					</valueList>
					<title>Type</title>
					<align>left</align>
					<methods>
						<add />
						<view />
						<list />
						<edit />
						<remove />
					</methods>
					<validation>
						<isRequired>Name shouldn't be blank.</isRequired>
					</validation>
				</field>
				<field>
					<name>name</name>
					<type>varchar</type>
					<title>Name</title>
					<align>left</align>
					<methods>
						<add />
						<view />
						<list />
						<edit />
						<remove />
					</methods>
					<validation>
						<required />
					</validation>
				</field>
				<field>
					<name>slug</name>
					<type>varchar</type>
					<title>Slug</title>
					<align>left</align>
					<methods>
						<add />
						<view />
						<list />
						<edit />
						<remove />
					</methods>
				</field>
				<field>
					<name>createdate</name>
					<type>datetime</type>
					<title>Date</title>
					<align>left</align>
					<methods>
						<add />
						<view />
						<list />
						<edit />
						<remove />
					</methods>
				</field>
			</fieldList>
		</zmodel>
	</zmodelList>
</scabbia>
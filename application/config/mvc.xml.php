<?xml version="1.0" encoding="utf-8" ?>
<!-- <?php exit(); ?> -->
<scabbia>
	<mvc>
		<routes>
			<link>{@siteroot}/?{@controller}/{@action}{@parameters}{@queryString}</link>
		</routes>

		<controllerList />

		<!-- _{@device}_{@language} -->
		<view>
			<namePattern>{@path}{@controller}/{@action}.{@extension}</namePattern>
			<errorPage>shared/error.cshtml</errorPage>
			<defaultViewExtension>cshtml</defaultViewExtension>
			<viewEngineList>
				<viewEngine>
					<extension>cshtml</extension>
					<class>viewengine_razor</class>
				</viewEngine>
			</viewEngineList>
		</view>
	</mvc>
</scabbia>
/*
Navicat PGSQL Data Transfer

Source Server         : survey
Source Server Version : 90107
Source Host           : office.goldsofttrading.com:5432
Source Database       : survey
Source Schema         : public

Target Server Type    : PGSQL
Target Server Version : 90107
File Encoding         : 65001

Date: 2013-01-02 07:23:19
*/


-- ----------------------------
-- Table structure for "public"."answers"
-- ----------------------------
DROP TABLE "public"."answers";
CREATE TABLE "public"."answers" (
"surveypublishid" uuid NOT NULL,
"questionid" uuid NOT NULL,
"surveyvisitorid" uuid NOT NULL,
"questionchoiceid" uuid,
"value" varchar(255)
)
WITH (OIDS=FALSE)

;
COMMENT ON COLUMN "public"."answers"."questionid" IS '* questionid must exists in each record since not all question types are multiple choice, questionchoices can be null.';

-- ----------------------------
-- Records of answers
-- ----------------------------
INSERT INTO "public"."answers" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', '0fcbb3d1-acdf-43ad-8224-656def5df943', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '8f944d47-92a8-4f96-bfb6-dd7e5b04e294', null);
INSERT INTO "public"."answers" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '4dd2f651-32e1-4e53-91a4-dff674fe69c0', '');
INSERT INTO "public"."answers" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '41131ae1-6688-48e6-911f-594e0fee5ed3', '');
INSERT INTO "public"."answers" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', '77419f07-dc3d-4ff6-9883-a3c846481440', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '209a5143-738a-476c-a3f0-9a37f3fc9a58', '');
INSERT INTO "public"."answers" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', '7b6214ca-e47c-4411-a953-19c27d36127b', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '66944214-5fe3-4b27-937f-23ead73d032f', '');
INSERT INTO "public"."answers" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', '96dbaae9-276c-480a-843e-2535b0fbecbd', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', null, null);
INSERT INTO "public"."answers" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', 'ad956460-3592-41bf-8a64-71d3783e3449', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', 'af280341-9a42-41ba-be41-4f1572cfc4a1', null);
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '0fcbb3d1-acdf-43ad-8224-656def5df943', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '8f944d47-92a8-4f96-bfb6-dd7e5b04e294', null);
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '0fcbb3d1-acdf-43ad-8224-656def5df943', '9514aab5-168d-4c0d-b58f-9c8fb119b0f3', '8f944d47-92a8-4f96-bfb6-dd7e5b04e294', null);
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '0fcbb3d1-acdf-43ad-8224-656def5df943', 'f759f53a-016c-4ddb-979b-7149d915d22d', '35ea5168-c91e-4eec-9d8b-7850081315df', null);
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '6e2e8931-4107-414c-b46a-bf557f4cb85a', 'skirt');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', '9514aab5-168d-4c0d-b58f-9c8fb119b0f3', '2d80235f-ea88-4f04-af69-ce8e622b030a', '');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', 'f759f53a-016c-4ddb-979b-7149d915d22d', '6e2e8931-4107-414c-b46a-bf557f4cb85a', 'skirt');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '41131ae1-6688-48e6-911f-594e0fee5ed3', '');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', '9514aab5-168d-4c0d-b58f-9c8fb119b0f3', '41e22a12-4912-4cd3-8195-b2fa2ee9798c', '');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', 'f759f53a-016c-4ddb-979b-7149d915d22d', '41e22a12-4912-4cd3-8195-b2fa2ee9798c', '');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '77419f07-dc3d-4ff6-9883-a3c846481440', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '209a5143-738a-476c-a3f0-9a37f3fc9a58', '');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '77419f07-dc3d-4ff6-9883-a3c846481440', '9514aab5-168d-4c0d-b58f-9c8fb119b0f3', 'a3556a31-299a-414c-a34a-4339a179d5d3', 'hmm');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '77419f07-dc3d-4ff6-9883-a3c846481440', 'f759f53a-016c-4ddb-979b-7149d915d22d', 'e1f4f8d1-32d6-408d-9be6-d5429e4f2b10', '');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '7b6214ca-e47c-4411-a953-19c27d36127b', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', 'b32e5393-9903-46e0-ad8c-7d765fe4b97f', 'David Gilmour');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '7b6214ca-e47c-4411-a953-19c27d36127b', '9514aab5-168d-4c0d-b58f-9c8fb119b0f3', 'b32e5393-9903-46e0-ad8c-7d765fe4b97f', 'Ritchie Blackmore');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '7b6214ca-e47c-4411-a953-19c27d36127b', 'f759f53a-016c-4ddb-979b-7149d915d22d', 'ea98ac7f-5c98-4f5e-a27a-3767f3253640', '');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '96dbaae9-276c-480a-843e-2535b0fbecbd', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', null, '5');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '96dbaae9-276c-480a-843e-2535b0fbecbd', '9514aab5-168d-4c0d-b58f-9c8fb119b0f3', null, null);
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '96dbaae9-276c-480a-843e-2535b0fbecbd', 'f759f53a-016c-4ddb-979b-7149d915d22d', null, '3');
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', 'ad956460-3592-41bf-8a64-71d3783e3449', '707c1e4c-74fb-40ca-9d52-bdedfaee8164', '1724d45a-56d0-4c60-ae33-a8476f8d480f', null);
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', 'ad956460-3592-41bf-8a64-71d3783e3449', '9514aab5-168d-4c0d-b58f-9c8fb119b0f3', '1724d45a-56d0-4c60-ae33-a8476f8d480f', null);
INSERT INTO "public"."answers" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', 'ad956460-3592-41bf-8a64-71d3783e3449', 'f759f53a-016c-4ddb-979b-7149d915d22d', '70b6d897-357e-46a5-9dd0-7a19ab6be4f5', null);

-- ----------------------------
-- Table structure for "public"."categories"
-- ----------------------------
DROP TABLE "public"."categories";
CREATE TABLE "public"."categories" (
"categoryid" uuid NOT NULL,
"name" varchar(50)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO "public"."categories" VALUES ('0f26a682-5810-425c-8e08-c1e7c2e371a5', 'Events');
INSERT INTO "public"."categories" VALUES ('0fefdfcb-a041-4d81-aadb-ac1a2005a277', 'Community');
INSERT INTO "public"."categories" VALUES ('14073a62-83b9-47b4-a40c-b62e5455c369', 'Healthcare');
INSERT INTO "public"."categories" VALUES ('460f4d99-2232-44bc-860f-93857f150ae5', 'Other');
INSERT INTO "public"."categories" VALUES ('614a9811-11c5-4352-9cd8-b9578dd7c9b2', 'Just for Fun');
INSERT INTO "public"."categories" VALUES ('86bae541-83a8-4a6b-938d-ef5422303d7e', 'Industry Specific');
INSERT INTO "public"."categories" VALUES ('95085a32-5fb0-47ef-80ac-ba0885ddde02', 'Market Research');
INSERT INTO "public"."categories" VALUES ('a72411f4-3f4b-4973-8e7d-d37cfca537b6', 'Political');
INSERT INTO "public"."categories" VALUES ('bc78e3e1-5d0f-4c41-aa67-da9cd593a7dc', 'Human Resources');
INSERT INTO "public"."categories" VALUES ('cf9b808b-483c-4682-8089-99e811287890', 'Non-Profit');
INSERT INTO "public"."categories" VALUES ('d29026fe-15ee-470c-ac70-83d00a798c7a', 'Demographics');
INSERT INTO "public"."categories" VALUES ('dcf132a1-3cd6-4d4f-888a-7cfe63965811', 'Education');
INSERT INTO "public"."categories" VALUES ('e7287de1-225a-4122-ab02-ba49c9589d2b', 'Customer Feedback');

-- ----------------------------
-- Table structure for "public"."faqcategories"
-- ----------------------------
DROP TABLE "public"."faqcategories";
CREATE TABLE "public"."faqcategories" (
"faqcategoryid" uuid NOT NULL,
"name" varchar(50) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of faqcategories
-- ----------------------------
INSERT INTO "public"."faqcategories" VALUES ('0f8d6ccf-58b3-4318-9340-89afad37b8fc', 'Surveys');
INSERT INTO "public"."faqcategories" VALUES ('bcc43f53-b1b2-4f67-8216-2a35536e3f0f', 'Usage');

-- ----------------------------
-- Table structure for "public"."faqcontents"
-- ----------------------------
DROP TABLE "public"."faqcontents";
CREATE TABLE "public"."faqcontents" (
"faqcontentid" uuid NOT NULL,
"faqcategoryid" uuid NOT NULL,
"title" varchar(255) NOT NULL,
"content" text NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of faqcontents
-- ----------------------------
INSERT INTO "public"."faqcontents" VALUES ('0a7e0de1-961f-494f-9b5d-7f4436253bd3', 'bcc43f53-b1b2-4f67-8216-2a35536e3f0f', 'Why survey-e-bot?', 'Survey-a-bot is an advanced system that ...');
INSERT INTO "public"."faqcontents" VALUES ('ae09b186-836a-46e7-8b56-c5695ded48ba', 'bcc43f53-b1b2-4f67-8216-2a35536e3f0f', 'Who are the administrators?', 'Us');
INSERT INTO "public"."faqcontents" VALUES ('d8d97692-3a5c-4285-9d44-34813dfa062e', '0f8d6ccf-58b3-4318-9340-89afad37b8fc', 'What is survey', 'Survey is a system...');

-- ----------------------------
-- Table structure for "public"."languages"
-- ----------------------------
DROP TABLE "public"."languages";
CREATE TABLE "public"."languages" (
"languageid" char(2) NOT NULL,
"name" varchar(50) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO "public"."languages" VALUES ('en', 'English');
INSERT INTO "public"."languages" VALUES ('tr', 'Turkish');

-- ----------------------------
-- Table structure for "public"."posts"
-- ----------------------------
DROP TABLE "public"."posts";
CREATE TABLE "public"."posts" (
"postid" uuid NOT NULL,
"createdate" timestamp(6) NOT NULL,
"ownerid" uuid NOT NULL,
"title" varchar(100) NOT NULL,
"content" text NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of posts
-- ----------------------------
INSERT INTO "public"."posts" VALUES ('0ca749c2-82ab-4a15-b44f-7a9385f581a3', '2013-01-01 22:38:52', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'test', 'test');
INSERT INTO "public"."posts" VALUES ('8e925f49-e59a-4e47-9818-8bf88618e5cd', '2012-12-13 18:11:47', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Hello World', 'Hello');

-- ----------------------------
-- Table structure for "public"."questionchoices"
-- ----------------------------
DROP TABLE "public"."questionchoices";
CREATE TABLE "public"."questionchoices" (
"questionchoiceid" uuid NOT NULL,
"questionid" uuid NOT NULL,
"content" text NOT NULL,
"type" int2 DEFAULT 0 NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of questionchoices
-- ----------------------------
INSERT INTO "public"."questionchoices" VALUES ('01305a9a-d79e-491b-9fd3-e295b2ccea88', '7413850c-3ff9-47d9-9d21-9ad1242e4377', 'I am undecided. ', '0');
INSERT INTO "public"."questionchoices" VALUES ('0f1c8d8b-ccfa-47e9-8548-36f69873ac58', '85f14447-5466-406c-8467-265728e04b7a', 'bad', '3');
INSERT INTO "public"."questionchoices" VALUES ('1603df36-f163-4b44-9346-34d77648718a', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', 'Other', '3');
INSERT INTO "public"."questionchoices" VALUES ('1724d45a-56d0-4c60-ae33-a8476f8d480f', 'ad956460-3592-41bf-8a64-71d3783e3449', 'Not at all', '0');
INSERT INTO "public"."questionchoices" VALUES ('209a5143-738a-476c-a3f0-9a37f3fc9a58', '77419f07-dc3d-4ff6-9883-a3c846481440', 'fine', '2');
INSERT INTO "public"."questionchoices" VALUES ('27341696-9fef-4a49-a8af-af1aa496762a', '4df64e10-63a6-48bd-92f7-39bb7a05fef2', 'I am for it. ', '0');
INSERT INTO "public"."questionchoices" VALUES ('2d80235f-ea88-4f04-af69-ce8e622b030a', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', 'A jacket', '0');
INSERT INTO "public"."questionchoices" VALUES ('2dc96b75-7770-49cd-a93c-b7e8f67de497', '4df64e10-63a6-48bd-92f7-39bb7a05fef2', 'I am undecided.', '0');
INSERT INTO "public"."questionchoices" VALUES ('3176e1e8-0b07-471d-bb74-2431f0acb2c6', '0fcbb3d1-acdf-43ad-8224-656def5df943', 'Night', '0');
INSERT INTO "public"."questionchoices" VALUES ('35ea5168-c91e-4eec-9d8b-7850081315df', '0fcbb3d1-acdf-43ad-8224-656def5df943', 'Afternoon', '0');
INSERT INTO "public"."questionchoices" VALUES ('3e9feaf8-d1b7-4817-9fa9-75b058da6002', '4df64e10-63a6-48bd-92f7-39bb7a05fef2', ' I am against it. ', '0');
INSERT INTO "public"."questionchoices" VALUES ('41131ae1-6688-48e6-911f-594e0fee5ed3', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', 'Yes', '0');
INSERT INTO "public"."questionchoices" VALUES ('41e22a12-4912-4cd3-8195-b2fa2ee9798c', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', 'No', '0');
INSERT INTO "public"."questionchoices" VALUES ('4dd2f651-32e1-4e53-91a4-dff674fe69c0', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', 'A hat', '0');
INSERT INTO "public"."questionchoices" VALUES ('59bcfcc1-71e1-4e71-8cca-8fee796788a4', '7413850c-3ff9-47d9-9d21-9ad1242e4377', ' I am pro-life. ', '0');
INSERT INTO "public"."questionchoices" VALUES ('64e440b4-e452-4b9a-b160-c4734741e481', '64684d8f-a3bb-4f26-b650-ad62f50f487a', ' Yes, we need to act now. ', '0');
INSERT INTO "public"."questionchoices" VALUES ('66944214-5fe3-4b27-937f-23ead73d032f', '7b6214ca-e47c-4411-a953-19c27d36127b', 'Michael Jackson', '0');
INSERT INTO "public"."questionchoices" VALUES ('6e2e8931-4107-414c-b46a-bf557f4cb85a', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', 'Other', '3');
INSERT INTO "public"."questionchoices" VALUES ('70b6d897-357e-46a5-9dd0-7a19ab6be4f5', 'ad956460-3592-41bf-8a64-71d3783e3449', 'Maybe', '0');
INSERT INTO "public"."questionchoices" VALUES ('79cf1844-057b-46e6-88d0-216efb7bfb04', '7413850c-3ff9-47d9-9d21-9ad1242e4377', ' I am pro-choice. ', '0');
INSERT INTO "public"."questionchoices" VALUES ('8f944d47-92a8-4f96-bfb6-dd7e5b04e294', '0fcbb3d1-acdf-43ad-8224-656def5df943', 'Morning', '0');
INSERT INTO "public"."questionchoices" VALUES ('92379a54-22a2-4c0a-91cd-5ee9c47d470d', '85f14447-5466-406c-8467-265728e04b7a', 'good', '3');
INSERT INTO "public"."questionchoices" VALUES ('95aafbbf-a97c-47c7-bdff-12765c6fc2bb', 'a314fa21-0f23-4b2d-af6c-31c9182f024a', 'bad', '3');
INSERT INTO "public"."questionchoices" VALUES ('a3556a31-299a-414c-a34a-4339a179d5d3', '77419f07-dc3d-4ff6-9883-a3c846481440', 'bad', '2');
INSERT INTO "public"."questionchoices" VALUES ('a4dc2b3d-f05c-422d-82bb-8d08f0ec4acc', 'a314fa21-0f23-4b2d-af6c-31c9182f024a', 'good', '1');
INSERT INTO "public"."questionchoices" VALUES ('af280341-9a42-41ba-be41-4f1572cfc4a1', 'ad956460-3592-41bf-8a64-71d3783e3449', 'Very', '0');
INSERT INTO "public"."questionchoices" VALUES ('b32e5393-9903-46e0-ad8c-7d765fe4b97f', '7b6214ca-e47c-4411-a953-19c27d36127b', 'Other, please specify:', '1');
INSERT INTO "public"."questionchoices" VALUES ('b89939b4-cfb1-4045-a420-4c448a427128', '64684d8f-a3bb-4f26-b650-ad62f50f487a', ' No, I am not concerned.', '0');
INSERT INTO "public"."questionchoices" VALUES ('ba2b23b8-7601-4dfb-871a-10dfb285d001', '64684d8f-a3bb-4f26-b650-ad62f50f487a', 'I am undecided.', '0');
INSERT INTO "public"."questionchoices" VALUES ('c1c1ebb8-3710-4a3d-b71d-ccbec7f27f0e', 'a314fa21-0f23-4b2d-af6c-31c9182f024a', 'natural', '2');
INSERT INTO "public"."questionchoices" VALUES ('c1e901b9-7ee5-422f-a539-b302ad073894', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6', 'An umbrella', '0');
INSERT INTO "public"."questionchoices" VALUES ('e1f4f8d1-32d6-408d-9be6-d5429e4f2b10', '77419f07-dc3d-4ff6-9883-a3c846481440', 'welll', '2');
INSERT INTO "public"."questionchoices" VALUES ('e42e3eea-1fb8-4fd5-9f73-6ceedf70a3bd', '85f14447-5466-406c-8467-265728e04b7a', 'neutra', '3');
INSERT INTO "public"."questionchoices" VALUES ('ea98ac7f-5c98-4f5e-a27a-3767f3253640', '7b6214ca-e47c-4411-a953-19c27d36127b', 'Madonna', '0');
INSERT INTO "public"."questionchoices" VALUES ('ef3f59d8-c296-401f-aa59-a50a26700061', '0fcbb3d1-acdf-43ad-8224-656def5df943', 'Midnight', '0');
INSERT INTO "public"."questionchoices" VALUES ('f34376f5-afc0-4203-87a1-79bb5b444e62', '86e1de6b-4933-4d48-8468-c91134efffa7', '', '0');

-- ----------------------------
-- Table structure for "public"."questionchoicetypes"
-- ----------------------------
DROP TABLE "public"."questionchoicetypes";
CREATE TABLE "public"."questionchoicetypes" (
"questionchoicetypeid" int2 NOT NULL,
"name" varchar(30) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of questionchoicetypes
-- ----------------------------
INSERT INTO "public"."questionchoicetypes" VALUES ('0', 'No Input');
INSERT INTO "public"."questionchoicetypes" VALUES ('1', 'Any');
INSERT INTO "public"."questionchoicetypes" VALUES ('2', 'Numeric');
INSERT INTO "public"."questionchoicetypes" VALUES ('3', 'Alphanumeric');

-- ----------------------------
-- Table structure for "public"."questions"
-- ----------------------------
DROP TABLE "public"."questions";
CREATE TABLE "public"."questions" (
"questionid" uuid NOT NULL,
"ownerid" uuid NOT NULL,
"content" text NOT NULL,
"type" int2 NOT NULL,
"typefilter" int2 NOT NULL,
"isshared" bit(1) NOT NULL,
"enabled" bit(1) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of questions
-- ----------------------------
INSERT INTO "public"."questions" VALUES ('0364268c-b424-4d77-b274-ccb33580ae0a', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Do you think it is easy to create a survey with this application', '0', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('0fcbb3d1-acdf-43ad-8224-656def5df943', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'What time is it?', '1', '1', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('357b255a-f37f-4c4d-8dc8-9f626eaff6d6', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Which is the most preferable one?', '1', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('3b5eaf2e-b57b-4d73-8104-69fe0a8acb04', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Is it cold outside?', '1', '0', E'0', E'1');
INSERT INTO "public"."questions" VALUES ('4666b9ee-24bc-4a9a-b1de-c53d72da5e34', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'How many hours you study on this course?', '2', '1', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('4ca48676-8af5-464c-a352-a58ae0f242a1', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Grade whatever you want', '0', '0', E'1', E'0');
INSERT INTO "public"."questions" VALUES ('4ce7f6c3-892a-41d8-bc7b-ec80551d82e9', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Is it necessary?', '0', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('4df64e10-63a6-48bd-92f7-39bb7a05fef2', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', 'How do you feel about the war in Iraq?', '1', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('524cca21-c345-462f-92c9-f2f761cd012b', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'testing part is good?', '0', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('57139f5b-68c0-4e51-a8f2-83a22926f3b3', '548b6a3e-8740-4121-ae2a-50bd530bc501', 'do we continue to talk politics or not', '2', '2', E'0', E'1');
INSERT INTO "public"."questions" VALUES ('64684d8f-a3bb-4f26-b650-ad62f50f487a', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', 'Do you think global warming is an immediate threat?', '1', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('7413850c-3ff9-47d9-9d21-9ad1242e4377', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', 'What is your stance on abortion rights?', '1', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('77419f07-dc3d-4ff6-9883-a3c846481440', '548b6a3e-8740-4121-ae2a-50bd530bc501', 'Politics, how does it work in Nigeria', '1', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('7785546b-416e-41de-9926-63af06ce2add', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Is it needed?', '0', '0', E'1', E'0');
INSERT INTO "public"."questions" VALUES ('7b6214ca-e47c-4411-a953-19c27d36127b', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Which one is do you like to most?', '1', '0', E'0', E'1');
INSERT INTO "public"."questions" VALUES ('85f14447-5466-406c-8467-265728e04b7a', '548b6a3e-8740-4121-ae2a-50bd530bc501', 'Politics in EMU is it good or bad', '1', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('86e1de6b-4933-4d48-8468-c91134efffa7', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Was it helpful?', '0', '0', E'0', E'1');
INSERT INTO "public"."questions" VALUES ('96dbaae9-276c-480a-843e-2535b0fbecbd', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Grade your instructor out of 10', '2', '1', E'1', E'0');
INSERT INTO "public"."questions" VALUES ('a314fa21-0f23-4b2d-af6c-31c9182f024a', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'how is going on?', '1', '0', E'0', E'0');
INSERT INTO "public"."questions" VALUES ('a826f231-34d5-4588-8948-9382c8aaa424', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Do you think it is easy to create a survey with this application', '0', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('ad956460-3592-41bf-8a64-71d3783e3449', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Is this course interesting for you?', '1', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('b57aa633-e262-471a-a789-a26f7df1aa85', '548b6a3e-8740-4121-ae2a-50bd530bc501', 'do you love politics', '2', '2', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('da56da87-b4c5-4bbe-89f7-543cbe11d29e', '445a7c07-f4db-4194-8f88-5c4b32019f7a', 'How many hours do you spend to watch TV series?', '2', '0', E'1', E'1');
INSERT INTO "public"."questions" VALUES ('da659592-4866-4c43-99e3-cb0a5469e9c3', '548b6a3e-8740-4121-ae2a-50bd530bc501', 'oline survey should be stoped', '0', '0', E'1', E'1');

-- ----------------------------
-- Table structure for "public"."questiontypefilters"
-- ----------------------------
DROP TABLE "public"."questiontypefilters";
CREATE TABLE "public"."questiontypefilters" (
"questiontypefilterid" int2 NOT NULL,
"name" varchar(30) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of questiontypefilters
-- ----------------------------
INSERT INTO "public"."questiontypefilters" VALUES ('0', 'Any');
INSERT INTO "public"."questiontypefilters" VALUES ('1', 'Numeric');
INSERT INTO "public"."questiontypefilters" VALUES ('2', 'Alphanumeric');

-- ----------------------------
-- Table structure for "public"."questiontypes"
-- ----------------------------
DROP TABLE "public"."questiontypes";
CREATE TABLE "public"."questiontypes" (
"questiontypeid" int2 NOT NULL,
"name" varchar(30) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of questiontypes
-- ----------------------------
INSERT INTO "public"."questiontypes" VALUES ('0', 'Evaluation');
INSERT INTO "public"."questiontypes" VALUES ('1', 'Multiple Choice');
INSERT INTO "public"."questiontypes" VALUES ('2', 'Fill In The Blanks');

-- ----------------------------
-- Table structure for "public"."surveypublishs"
-- ----------------------------
DROP TABLE "public"."surveypublishs";
CREATE TABLE "public"."surveypublishs" (
"surveypublishid" uuid NOT NULL,
"surveyid" uuid NOT NULL,
"revision" int8 NOT NULL,
"ownerid" uuid NOT NULL,
"createdate" timestamp(6) NOT NULL,
"startdate" timestamp(6) NOT NULL,
"enddate" timestamp(6),
"password" varchar(30),
"type" int2 NOT NULL,
"userlimit" int8 NOT NULL,
"enabled" bit(1) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of surveypublishs
-- ----------------------------
INSERT INTO "public"."surveypublishs" VALUES ('20513b70-df2e-4ff8-95fc-81f9f627a345', '15f77288-a8bc-4a49-a413-d1333ef3cbf5', '1', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2012-12-24 23:09:12', '2012-11-01 00:00:00', '2013-01-31 00:00:00', '123', '1', '50', E'1');
INSERT INTO "public"."surveypublishs" VALUES ('27f6342d-59e8-48b8-8612-d2ddf1d4f444', 'b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '2', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', '2013-01-01 17:01:32', '2013-01-01 00:00:00', '2013-01-17 00:00:00', '', '0', '0', E'1');
INSERT INTO "public"."surveypublishs" VALUES ('453526d0-1583-4874-9376-47d2a3c23d3d', '15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2013-01-02 02:05:16', '2013-01-01 00:00:00', '2013-01-03 00:00:00', '123', '1', '0', E'1');
INSERT INTO "public"."surveypublishs" VALUES ('5244427c-823c-4cb8-b7b1-206ca3baa643', 'b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '1', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', '2013-01-02 01:57:03', '2013-01-02 00:00:00', '2013-01-17 00:00:00', '7142461', '0', '0', E'1');
INSERT INTO "public"."surveypublishs" VALUES ('735929e4-576d-4393-9911-046370c690de', '411d1c8a-3cb7-4318-91e5-6411624a6a29', '1', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2013-01-01 22:09:52', '2013-01-01 00:00:00', '1999-11-30 00:00:00', 'admin', '0', '0', E'1');
INSERT INTO "public"."surveypublishs" VALUES ('ab87ce04-0623-4db5-8588-114c8e7e0266', '15f77288-a8bc-4a49-a413-d1333ef3cbf5', '1', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2012-12-24 23:09:41', '2013-03-01 00:00:00', null, '123', '1', '0', E'1');
INSERT INTO "public"."surveypublishs" VALUES ('c4920ddd-4204-4537-8d5e-d953cedb5d11', '15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2013-01-02 07:29:08', '2013-01-02 00:00:00', null, '', '1', '0', E'1');
INSERT INTO "public"."surveypublishs" VALUES ('da1c75a4-e586-4a7b-bcad-643ab9bb00f4', '15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2013-01-01 19:52:08', '2013-01-01 00:00:00', '1999-11-30 00:00:00', 'admin', '0', '0', E'1');

-- ----------------------------
-- Table structure for "public"."surveypublishtypes"
-- ----------------------------
DROP TABLE "public"."surveypublishtypes";
CREATE TABLE "public"."surveypublishtypes" (
"surveypublishtype" int2 NOT NULL,
"name" varchar(30) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of surveypublishtypes
-- ----------------------------
INSERT INTO "public"."surveypublishtypes" VALUES ('0', 'Auth-Only');
INSERT INTO "public"."surveypublishtypes" VALUES ('1', 'Public');

-- ----------------------------
-- Table structure for "public"."surveyquestions"
-- ----------------------------
DROP TABLE "public"."surveyquestions";
CREATE TABLE "public"."surveyquestions" (
"surveyid" uuid NOT NULL,
"revision" int8 NOT NULL,
"questionid" uuid NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of surveyquestions
-- ----------------------------
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '1', '7b6214ca-e47c-4411-a953-19c27d36127b');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '2', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '2', '7785546b-416e-41de-9926-63af06ce2add');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '2', '7b6214ca-e47c-4411-a953-19c27d36127b');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '2', '86e1de6b-4933-4d48-8468-c91134efffa7');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '2', '96dbaae9-276c-480a-843e-2535b0fbecbd');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '2', 'ad956460-3592-41bf-8a64-71d3783e3449');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '0fcbb3d1-acdf-43ad-8224-656def5df943');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '357b255a-f37f-4c4d-8dc8-9f626eaff6d6');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '77419f07-dc3d-4ff6-9883-a3c846481440');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '7b6214ca-e47c-4411-a953-19c27d36127b');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '96dbaae9-276c-480a-843e-2535b0fbecbd');
INSERT INTO "public"."surveyquestions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', 'ad956460-3592-41bf-8a64-71d3783e3449');
INSERT INTO "public"."surveyquestions" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '1', '4df64e10-63a6-48bd-92f7-39bb7a05fef2');
INSERT INTO "public"."surveyquestions" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '1', '7413850c-3ff9-47d9-9d21-9ad1242e4377');
INSERT INTO "public"."surveyquestions" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '2', '4df64e10-63a6-48bd-92f7-39bb7a05fef2');
INSERT INTO "public"."surveyquestions" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '2', '64684d8f-a3bb-4f26-b650-ad62f50f487a');
INSERT INTO "public"."surveyquestions" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '2', '7413850c-3ff9-47d9-9d21-9ad1242e4377');
INSERT INTO "public"."surveyquestions" VALUES ('c06ed9f0-8219-4229-8f79-addeef4cc16d', '1', '3b5eaf2e-b57b-4d73-8104-69fe0a8acb04');
INSERT INTO "public"."surveyquestions" VALUES ('c06ed9f0-8219-4229-8f79-addeef4cc16d', '1', '524cca21-c345-462f-92c9-f2f761cd012b');
INSERT INTO "public"."surveyquestions" VALUES ('c06ed9f0-8219-4229-8f79-addeef4cc16d', '1', '7b6214ca-e47c-4411-a953-19c27d36127b');
INSERT INTO "public"."surveyquestions" VALUES ('c06ed9f0-8219-4229-8f79-addeef4cc16d', '1', 'a314fa21-0f23-4b2d-af6c-31c9182f024a');
INSERT INTO "public"."surveyquestions" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '1', '77419f07-dc3d-4ff6-9883-a3c846481440');
INSERT INTO "public"."surveyquestions" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '1', '7785546b-416e-41de-9926-63af06ce2add');
INSERT INTO "public"."surveyquestions" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '1', '85f14447-5466-406c-8467-265728e04b7a');
INSERT INTO "public"."surveyquestions" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '1', 'b57aa633-e262-471a-a789-a26f7df1aa85');
INSERT INTO "public"."surveyquestions" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '1', 'da56da87-b4c5-4bbe-89f7-543cbe11d29e');
INSERT INTO "public"."surveyquestions" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '1', 'da659592-4866-4c43-99e3-cb0a5469e9c3');
INSERT INTO "public"."surveyquestions" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '1', '4666b9ee-24bc-4a9a-b1de-c53d72da5e34');
INSERT INTO "public"."surveyquestions" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '1', '7b6214ca-e47c-4411-a953-19c27d36127b');
INSERT INTO "public"."surveyquestions" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '2', '4666b9ee-24bc-4a9a-b1de-c53d72da5e34');
INSERT INTO "public"."surveyquestions" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '2', '7b6214ca-e47c-4411-a953-19c27d36127b');
INSERT INTO "public"."surveyquestions" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '2', '96dbaae9-276c-480a-843e-2535b0fbecbd');

-- ----------------------------
-- Table structure for "public"."surveyrevisions"
-- ----------------------------
DROP TABLE "public"."surveyrevisions";
CREATE TABLE "public"."surveyrevisions" (
"surveyid" uuid NOT NULL,
"revision" int8 NOT NULL,
"createdate" timestamp(6) NOT NULL,
"ownerid" uuid NOT NULL,
"details" text NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of surveyrevisions
-- ----------------------------
INSERT INTO "public"."surveyrevisions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '1', '2012-12-20 00:00:00', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');
INSERT INTO "public"."surveyrevisions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '2', '2012-12-31 18:50:00', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');
INSERT INTO "public"."surveyrevisions" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '3', '2012-12-31 19:25:00', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');
INSERT INTO "public"."surveyrevisions" VALUES ('411d1c8a-3cb7-4318-91e5-6411624a6a29', '1', '2012-12-31 17:50:16', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');
INSERT INTO "public"."surveyrevisions" VALUES ('758d57ef-bce9-4416-abb5-30f8192f37d2', '1', '2012-12-31 17:50:23', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');
INSERT INTO "public"."surveyrevisions" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '1', '2013-01-01 16:48:43', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', '');
INSERT INTO "public"."surveyrevisions" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '2', '2013-01-01 16:59:53', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', '');
INSERT INTO "public"."surveyrevisions" VALUES ('c06ed9f0-8219-4229-8f79-addeef4cc16d', '1', '2013-01-01 15:01:12', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');
INSERT INTO "public"."surveyrevisions" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '1', '2013-01-01 06:15:43', '548b6a3e-8740-4121-ae2a-50bd530bc501', '');
INSERT INTO "public"."surveyrevisions" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '1', '2012-12-20 00:00:00', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');
INSERT INTO "public"."surveyrevisions" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '2', '2013-01-01 21:57:46', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '');

-- ----------------------------
-- Table structure for "public"."surveys"
-- ----------------------------
DROP TABLE "public"."surveys";
CREATE TABLE "public"."surveys" (
"surveyid" uuid NOT NULL,
"ownerid" uuid NOT NULL,
"createdate" timestamp(6) NOT NULL,
"title" varchar(100) NOT NULL,
"categoryid" uuid NOT NULL,
"themeid" uuid NOT NULL,
"languageid" char(2) NOT NULL,
"description" text NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of surveys
-- ----------------------------
INSERT INTO "public"."surveys" VALUES ('15f77288-a8bc-4a49-a413-d1333ef3cbf5', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2012-12-24 00:00:00', 'Staff Evaluation', '0fefdfcb-a041-4d81-aadb-ac1a2005a277', '365dd456-e10c-43dc-87d9-17285bc5a90d', 'en', 'test3');
INSERT INTO "public"."surveys" VALUES ('411d1c8a-3cb7-4318-91e5-6411624a6a29', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2012-12-27 08:21:36', 'test', 'bc78e3e1-5d0f-4c41-aa67-da9cd593a7dc', '353a1816-0d3e-4a82-99b4-cd8095d0f94f', 'tr', 'test26');
INSERT INTO "public"."surveys" VALUES ('758d57ef-bce9-4416-abb5-30f8192f37d2', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2012-12-29 08:19:43', 'course evaluation', 'dcf132a1-3cd6-4d4f-888a-7cfe63965811', '353a1816-0d3e-4a82-99b4-cd8095d0f94f', 'en', 'to evaluate the specific course');
INSERT INTO "public"."surveys" VALUES ('b7cbc9e0-73ab-4518-a6a3-c7ec5ac5526a', '0a76ed21-1f6b-4f74-af49-7f6c3de7a442', '2013-01-01 16:43:11', 'seyma', 'a72411f4-3f4b-4973-8e7d-d37cfca537b6', '365dd456-e10c-43dc-87d9-17285bc5a90d', 'en', 'seyma');
INSERT INTO "public"."surveys" VALUES ('c06ed9f0-8219-4229-8f79-addeef4cc16d', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2013-01-01 14:59:49', 'test survey 01', '95085a32-5fb0-47ef-80ac-ba0885ddde02', '365dd456-e10c-43dc-87d9-17285bc5a90d', 'tr', 'testing');
INSERT INTO "public"."surveys" VALUES ('e9d8d4d1-6e41-4ef2-8186-06c3a83bf143', '548b6a3e-8740-4121-ae2a-50bd530bc501', '2013-01-01 06:12:28', 'Politics in EMU', 'a72411f4-3f4b-4973-8e7d-d37cfca537b6', '353a1816-0d3e-4a82-99b4-cd8095d0f94f', 'tr', 'Politics in EMU');
INSERT INTO "public"."surveys" VALUES ('ff785bfa-227d-4b0a-9d7e-3f31c4afa9b4', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '2012-12-23 00:00:00', 'Course Eval.', '0fefdfcb-a041-4d81-aadb-ac1a2005a277', '56682272-8d56-4bfd-84cc-027442687fe8', 'en', 'test');

-- ----------------------------
-- Table structure for "public"."surveyvisitors"
-- ----------------------------
DROP TABLE "public"."surveyvisitors";
CREATE TABLE "public"."surveyvisitors" (
"surveyvisitorid" uuid NOT NULL,
"surveypublishid" uuid NOT NULL,
"userid" uuid,
"ip" varchar(45) NOT NULL,
"useragent" varchar(255) NOT NULL,
"recorddate" timestamp(6) NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON COLUMN "public"."surveyvisitors"."ip" IS '* ipv6 considered';

-- ----------------------------
-- Records of surveyvisitors
-- ----------------------------
INSERT INTO "public"."surveyvisitors" VALUES ('707c1e4c-74fb-40ca-9d52-bdedfaee8164', '453526d0-1583-4874-9376-47d2a3c23d3d', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0', '2013-01-02 07:00:35');
INSERT INTO "public"."surveyvisitors" VALUES ('707c1e4c-74fb-40ca-9d52-bdedfaee8164', 'c4920ddd-4204-4537-8d5e-d953cedb5d11', '445a7c07-f4db-4194-8f88-5c4b32019f7a', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0', '2013-01-02 07:39:30');
INSERT INTO "public"."surveyvisitors" VALUES ('9514aab5-168d-4c0d-b58f-9c8fb119b0f3', 'c4920ddd-4204-4537-8d5e-d953cedb5d11', null, '127.0.0.1', 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.12', '2013-01-02 08:11:32');
INSERT INTO "public"."surveyvisitors" VALUES ('f759f53a-016c-4ddb-979b-7149d915d22d', 'c4920ddd-4204-4537-8d5e-d953cedb5d11', null, '::1', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)', '2013-01-02 07:42:56');

-- ----------------------------
-- Table structure for "public"."themes"
-- ----------------------------
DROP TABLE "public"."themes";
CREATE TABLE "public"."themes" (
"themeid" uuid NOT NULL,
"name" varchar(50) NOT NULL,
"cssrules" text NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of themes
-- ----------------------------
INSERT INTO "public"."themes" VALUES ('353a1816-0d3e-4a82-99b4-cd8095d0f94f', 'Default', '.question { }');
INSERT INTO "public"."themes" VALUES ('365dd456-e10c-43dc-87d9-17285bc5a90d', 'Blue', '.question { color: blue; }');
INSERT INTO "public"."themes" VALUES ('56682272-8d56-4bfd-84cc-027442687fe8', 'Bold', '.question { font-weight: bold !important; }');

-- ----------------------------
-- Table structure for "public"."users"
-- ----------------------------
DROP TABLE "public"."users";
CREATE TABLE "public"."users" (
"userid" uuid NOT NULL,
"displayname" varchar(100) NOT NULL,
"email" varchar(100) NOT NULL,
"phonenumber" varchar(15) NOT NULL,
"password" varchar(30) NOT NULL,
"facebookid" varchar(50),
"languageid" char(2) NOT NULL,
"firstname" varchar(50) NOT NULL,
"lastname" varchar(50) NOT NULL,
"logo" varchar(2048) NOT NULL,
"emailverification" varchar(8)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO "public"."users" VALUES ('0a76ed21-1f6b-4f74-af49-7f6c3de7a442', 'seyma', 'kacar.seyma@hotmail.com', '87778888', '7142461', null, 'en', 'seyma', 'kacar', 'logos/0a76ed21-1f6b-4f74-af49-7f6c3de7a442.png', null);
INSERT INTO "public"."users" VALUES ('1c02b618-0c75-4f43-8457-cf57d08f035d', 'Dummy User', 'laroux.pos@gmail.com', '445', 'test', null, 'tr', 'Dummy', 'User', '', null);
INSERT INTO "public"."users" VALUES ('2bdc618f-63e7-465c-aa28-bd72bd934833', 'Hasan Atbinici', 'hasanatbinici@gmail.com', '', 'chonok', '100001016591351', 'en', 'Hasan', 'Atbinici', '', null);
INSERT INTO "public"."users" VALUES ('3893a1c3-1eba-46bc-b421-64a5e7f845e2', 'Önder Özcan', 'onderozcan@gmail.com', '', 'wiphec', '717816184', 'en', 'Önder', 'Özcan', '', null);
INSERT INTO "public"."users" VALUES ('445a7c07-f4db-4194-8f88-5c4b32019f7a', 'Eser Özvataf', 'eser@sent.com', '', 'test', '503392685', 'en', 'Eser', 'Özvataf', 'logos/445a7c07-f4db-4194-8f88-5c4b32019f7a.png', null);
INSERT INTO "public"."users" VALUES ('548b6a3e-8740-4121-ae2a-50bd530bc501', 'Busaer Heedris Eniola', 'me2netalways@yahoo.ca', '', 'hihisp', '1348596395', 'en', 'Busaer', 'Eniola', '', null);
INSERT INTO "public"."users" VALUES ('8045eeb5-e112-4afa-b126-e5f567e9f5e0', 'test', 'fly_king_d12@hotmail.com', '05331234567', '123456', null, 'tr', 'testname', 'testsurname', '', null);
INSERT INTO "public"."users" VALUES ('8e211648-9600-44d7-b9b9-5e4f9c194c96', 'Barış Eşref Caymaz', 'mybaris1632@hotmail.com', '', 'pifriv', '567003826', 'en', 'Barış', 'Caymaz', '', null);
INSERT INTO "public"."users" VALUES ('91d0872a-47bf-4451-bd05-d5b628a92f5b', 'Idris', 'idris@test.com', '  ', '  ', null, 'en', 'İdris', '', '', null);
INSERT INTO "public"."users" VALUES ('a2b1eab8-61fe-43db-ab08-9c5fc4986d6a', 'Şeyma Kaçar', 'sheyma_kacar@hotmail.com', '', 'pradas', '796004416', 'en', 'Şeyma', 'Kaçar', '', null);
INSERT INTO "public"."users" VALUES ('aa22e7fc-fb16-4a15-830c-c61f3efe8591', 'Oğuzhan Küçer', 'oguzhankucer@mynet.com', '', 'prigul', '603054641', 'en', 'Oğuzhan', 'Küçer', '', null);
INSERT INTO "public"."users" VALUES ('af78408c-f4de-4ef0-8549-065f7cf3d993', 'Idris', 'loving-heart@live.com', '', 'test', null, 'en', 'idris', 'busari', 'logos/af78408c-f4de-4ef0-8549-065f7cf3d993.png', null);
INSERT INTO "public"."users" VALUES ('c67058c4-f5e2-423e-9c54-862c0b50f22c', 'Zeki Dikmen', 'zeki@me.com', '', 'spiped', '502259595', 'en', 'Zeki', 'Dikmen', '', null);
INSERT INTO "public"."users" VALUES ('e09f6a87-e393-4835-b27a-7218c8a48706', 'Şeyma Kaçar (2)', 'kacar.seyma@gmail.com', '', '7142461', null, 'en', 'Şeyma', 'Kaçar (2)', '', null);
INSERT INTO "public"."users" VALUES ('fb5f97af-a78a-4789-8232-010c09ed3009', 'Oguzhan', 'oguzhan@test.com', '  ', '  ', null, 'en', 'Oğuzhan', '', '', null);

-- ----------------------------
-- Alter Sequences Owned By 
-- ----------------------------

-- ----------------------------
-- Indexes structure for table answers
-- ----------------------------
CREATE INDEX "ix_answers_questionid" ON "public"."answers" USING btree ("questionid");

-- ----------------------------
-- Primary Key structure for table "public"."answers"
-- ----------------------------
ALTER TABLE "public"."answers" ADD PRIMARY KEY ("surveypublishid", "questionid", "surveyvisitorid");

-- ----------------------------
-- Primary Key structure for table "public"."categories"
-- ----------------------------
ALTER TABLE "public"."categories" ADD PRIMARY KEY ("categoryid");

-- ----------------------------
-- Uniques structure for table "public"."faqcategories"
-- ----------------------------
ALTER TABLE "public"."faqcategories" ADD UNIQUE ("name");

-- ----------------------------
-- Primary Key structure for table "public"."faqcategories"
-- ----------------------------
ALTER TABLE "public"."faqcategories" ADD PRIMARY KEY ("faqcategoryid");

-- ----------------------------
-- Primary Key structure for table "public"."faqcontents"
-- ----------------------------
ALTER TABLE "public"."faqcontents" ADD PRIMARY KEY ("faqcontentid");

-- ----------------------------
-- Primary Key structure for table "public"."languages"
-- ----------------------------
ALTER TABLE "public"."languages" ADD PRIMARY KEY ("languageid");

-- ----------------------------
-- Primary Key structure for table "public"."posts"
-- ----------------------------
ALTER TABLE "public"."posts" ADD PRIMARY KEY ("postid");

-- ----------------------------
-- Primary Key structure for table "public"."questionchoices"
-- ----------------------------
ALTER TABLE "public"."questionchoices" ADD PRIMARY KEY ("questionchoiceid");

-- ----------------------------
-- Primary Key structure for table "public"."questionchoicetypes"
-- ----------------------------
ALTER TABLE "public"."questionchoicetypes" ADD PRIMARY KEY ("questionchoicetypeid");

-- ----------------------------
-- Primary Key structure for table "public"."questions"
-- ----------------------------
ALTER TABLE "public"."questions" ADD PRIMARY KEY ("questionid");

-- ----------------------------
-- Primary Key structure for table "public"."questiontypefilters"
-- ----------------------------
ALTER TABLE "public"."questiontypefilters" ADD PRIMARY KEY ("questiontypefilterid");

-- ----------------------------
-- Primary Key structure for table "public"."questiontypes"
-- ----------------------------
ALTER TABLE "public"."questiontypes" ADD PRIMARY KEY ("questiontypeid");

-- ----------------------------
-- Indexes structure for table surveypublishs
-- ----------------------------
CREATE INDEX "ix_surveypublishs_surveyid" ON "public"."surveypublishs" USING btree ("surveyid");

-- ----------------------------
-- Primary Key structure for table "public"."surveypublishs"
-- ----------------------------
ALTER TABLE "public"."surveypublishs" ADD PRIMARY KEY ("surveypublishid");

-- ----------------------------
-- Primary Key structure for table "public"."surveypublishtypes"
-- ----------------------------
ALTER TABLE "public"."surveypublishtypes" ADD PRIMARY KEY ("surveypublishtype");

-- ----------------------------
-- Primary Key structure for table "public"."surveyquestions"
-- ----------------------------
ALTER TABLE "public"."surveyquestions" ADD PRIMARY KEY ("surveyid", "revision", "questionid");

-- ----------------------------
-- Indexes structure for table surveyrevisions
-- ----------------------------
CREATE INDEX "ix_surveyrevisions_surveyid" ON "public"."surveyrevisions" USING btree ("surveyid");

-- ----------------------------
-- Primary Key structure for table "public"."surveyrevisions"
-- ----------------------------
ALTER TABLE "public"."surveyrevisions" ADD PRIMARY KEY ("surveyid", "revision");

-- ----------------------------
-- Indexes structure for table surveys
-- ----------------------------
CREATE INDEX "ix_surveys_categoryid" ON "public"."surveys" USING btree ("categoryid");
CREATE INDEX "ix_surveys_ownerid" ON "public"."surveys" USING btree ("ownerid");

-- ----------------------------
-- Primary Key structure for table "public"."surveys"
-- ----------------------------
ALTER TABLE "public"."surveys" ADD PRIMARY KEY ("surveyid");

-- ----------------------------
-- Primary Key structure for table "public"."surveyvisitors"
-- ----------------------------
ALTER TABLE "public"."surveyvisitors" ADD PRIMARY KEY ("surveyvisitorid", "surveypublishid");

-- ----------------------------
-- Primary Key structure for table "public"."themes"
-- ----------------------------
ALTER TABLE "public"."themes" ADD PRIMARY KEY ("themeid");

-- ----------------------------
-- Indexes structure for table users
-- ----------------------------
CREATE UNIQUE INDEX "ix_users_email" ON "public"."users" USING btree ("email");
CREATE UNIQUE INDEX "ix_users_facebookid" ON "public"."users" USING btree ("facebookid");

-- ----------------------------
-- Primary Key structure for table "public"."users"
-- ----------------------------
ALTER TABLE "public"."users" ADD PRIMARY KEY ("userid");

-- ----------------------------
-- Foreign Key structure for table "public"."answers"
-- ----------------------------
ALTER TABLE "public"."answers" ADD FOREIGN KEY ("surveyvisitorid", "surveypublishid") REFERENCES "public"."surveyvisitors" ("surveyvisitorid", "surveypublishid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."answers" ADD FOREIGN KEY ("questionid") REFERENCES "public"."questions" ("questionid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."answers" ADD FOREIGN KEY ("questionchoiceid") REFERENCES "public"."questionchoices" ("questionchoiceid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."answers" ADD FOREIGN KEY ("surveypublishid") REFERENCES "public"."surveypublishs" ("surveypublishid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."faqcontents"
-- ----------------------------
ALTER TABLE "public"."faqcontents" ADD FOREIGN KEY ("faqcategoryid") REFERENCES "public"."faqcategories" ("faqcategoryid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."posts"
-- ----------------------------
ALTER TABLE "public"."posts" ADD FOREIGN KEY ("ownerid") REFERENCES "public"."users" ("userid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."questionchoices"
-- ----------------------------
ALTER TABLE "public"."questionchoices" ADD FOREIGN KEY ("type") REFERENCES "public"."questionchoicetypes" ("questionchoicetypeid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."questionchoices" ADD FOREIGN KEY ("questionid") REFERENCES "public"."questions" ("questionid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."questions"
-- ----------------------------
ALTER TABLE "public"."questions" ADD FOREIGN KEY ("type") REFERENCES "public"."questiontypes" ("questiontypeid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."questions" ADD FOREIGN KEY ("typefilter") REFERENCES "public"."questiontypefilters" ("questiontypefilterid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."questions" ADD FOREIGN KEY ("ownerid") REFERENCES "public"."users" ("userid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."surveypublishs"
-- ----------------------------
ALTER TABLE "public"."surveypublishs" ADD FOREIGN KEY ("ownerid") REFERENCES "public"."users" ("userid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveypublishs" ADD FOREIGN KEY ("surveyid", "revision") REFERENCES "public"."surveyrevisions" ("surveyid", "revision") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveypublishs" ADD FOREIGN KEY ("type") REFERENCES "public"."surveypublishtypes" ("surveypublishtype") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."surveyquestions"
-- ----------------------------
ALTER TABLE "public"."surveyquestions" ADD FOREIGN KEY ("surveyid", "revision") REFERENCES "public"."surveyrevisions" ("surveyid", "revision") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveyquestions" ADD FOREIGN KEY ("questionid") REFERENCES "public"."questions" ("questionid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."surveyrevisions"
-- ----------------------------
ALTER TABLE "public"."surveyrevisions" ADD FOREIGN KEY ("surveyid") REFERENCES "public"."surveys" ("surveyid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveyrevisions" ADD FOREIGN KEY ("ownerid") REFERENCES "public"."users" ("userid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."surveys"
-- ----------------------------
ALTER TABLE "public"."surveys" ADD FOREIGN KEY ("ownerid") REFERENCES "public"."users" ("userid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveys" ADD FOREIGN KEY ("categoryid") REFERENCES "public"."categories" ("categoryid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveys" ADD FOREIGN KEY ("themeid") REFERENCES "public"."themes" ("themeid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveys" ADD FOREIGN KEY ("languageid") REFERENCES "public"."languages" ("languageid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."surveyvisitors"
-- ----------------------------
ALTER TABLE "public"."surveyvisitors" ADD FOREIGN KEY ("userid") REFERENCES "public"."users" ("userid") ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE "public"."surveyvisitors" ADD FOREIGN KEY ("surveypublishid") REFERENCES "public"."surveypublishs" ("surveypublishid") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Key structure for table "public"."users"
-- ----------------------------
ALTER TABLE "public"."users" ADD FOREIGN KEY ("languageid") REFERENCES "public"."languages" ("languageid") ON DELETE RESTRICT ON UPDATE RESTRICT;

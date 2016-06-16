/*
Navicat PGSQL Data Transfer

Source Server         : PGSQL
Source Server Version : 90405
Source Host           : localhost:5432
Source Database       : iag
Source Schema         : public

Target Server Type    : PGSQL
Target Server Version : 90405
File Encoding         : 65001

Date: 2016-03-24 08:52:07
*/


-- ----------------------------
-- Sequence structure for clients_id_seq
-- ----------------------------
DROP SEQUENCE "public"."clients_id_seq";
CREATE SEQUENCE "public"."clients_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for users_id_seq
-- ----------------------------
DROP SEQUENCE "public"."users_id_seq";
CREATE SEQUENCE "public"."users_id_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 2
 CACHE 1;
SELECT setval('"public"."users_id_seq"', 2, true);

-- ----------------------------
-- Table structure for clients
-- ----------------------------
DROP TABLE IF EXISTS "public"."clients";
CREATE TABLE "public"."clients" (
"id" int4 DEFAULT nextval('clients_id_seq'::regclass) NOT NULL,
"first_name" varchar(255) COLLATE "default" NOT NULL,
"last_name" varchar(255) COLLATE "default" NOT NULL,
"email" varchar(255) COLLATE "default" NOT NULL,
"mobile_phone" varchar(255) COLLATE "default",
"home_phone" varchar(255) COLLATE "default",
"state" varchar(255) COLLATE "default",
"postcode" varchar(255) COLLATE "default",
"suburb" varchar(255) COLLATE "default",
"source" varchar(255) COLLATE "default",
"created_at" timestamp NOT NULL,
"updated_at" timestamp NOT NULL,
"deleted_at" timestamp
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of clients
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS "public"."migrations";
CREATE TABLE "public"."migrations" (
"migration" varchar(255) COLLATE "default" NOT NULL,
"batch" int4 NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO "public"."migrations" VALUES ('2014_10_12_000000_create_users_table', '1');
INSERT INTO "public"."migrations" VALUES ('2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO "public"."migrations" VALUES ('2016_03_17_102839_NewsTable', '2');
INSERT INTO "public"."migrations" VALUES ('2016_03_17_112943_TestTable', '3');
INSERT INTO "public"."migrations" VALUES ('2016_03_23_072716_create_clients_table', '4');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS "public"."password_resets";
CREATE TABLE "public"."password_resets" (
"email" varchar(255) COLLATE "default" NOT NULL,
"token" varchar(255) COLLATE "default" NOT NULL,
"created_at" timestamp NOT NULL,
"id" int4
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
INSERT INTO "public"."password_resets" VALUES ('henry.tran@qsoftvietnam.com', '134f8b5dd9d3d790ab6759e746b2fc5d', '2016-03-23 03:21:05', null);
INSERT INTO "public"."password_resets" VALUES ('henry.tran@qsoftvietnam.com', '45092916f1e6ea51f81fda164c44a77d', '2016-03-23 03:44:46', null);
INSERT INTO "public"."password_resets" VALUES ('henry.tran@qsoftvietnam.com', 'fd803b8d07557a3c226ddf33c1f3c878', '2016-03-23 03:45:25', null);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "public"."users";
CREATE TABLE "public"."users" (
"id" int4 DEFAULT nextval('users_id_seq'::regclass) NOT NULL,
"firstName" varchar(255) COLLATE "default" NOT NULL,
"lastName" varchar(255) COLLATE "default" NOT NULL,
"email" varchar(255) COLLATE "default" NOT NULL,
"password" varchar(60) COLLATE "default" NOT NULL,
"isActive" bool NOT NULL,
"isAdmin" bool NOT NULL,
"remember_token" varchar(100) COLLATE "default",
"created_at" timestamp NOT NULL,
"updated_at" timestamp NOT NULL,
"deleted_at" timestamp,
"avatar" varchar COLLATE "default",
"isSuperAdmin" bool
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO "public"."users" VALUES ('1', 'Henry', 'Tran', 'henry.tran@qsoftvietnam.com', '$2y$10$jfOXeW9aqtuP4QA0gzn1re6GEoTCsokcrNAmFISBx677PG1m4kx5q', 't', 't', 'EkFBeURZZtNoj0cgmpy9BIfPDkroPoiIJ6a0guz12N6RHeaRiLdyq8pXW3jg', '2016-03-17 17:07:27', '2016-03-24 01:33:44', null, null, 't');
INSERT INTO "public"."users" VALUES ('2', 'Hoai Nam', 'Tran', 'hoaitn@qsoftvietnam.com', '$2y$10$GHINGVJNZL1IqNgHlbbGPOQLIXgAX/H3QJkavvRxd7uFTTg1LfFLi', 't', 't', null, '2016-03-24 01:21:49', '2016-03-24 01:34:22', '2016-03-24 01:34:22', '92569.JPG', null);

-- ----------------------------
-- Alter Sequences Owned By 
-- ----------------------------
ALTER SEQUENCE "public"."clients_id_seq" OWNED BY "clients"."id";
ALTER SEQUENCE "public"."users_id_seq" OWNED BY "users"."id";

-- ----------------------------
-- Primary Key structure for table clients
-- ----------------------------
ALTER TABLE "public"."clients" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table password_resets
-- ----------------------------
CREATE INDEX "password_resets_email_index" ON "public"."password_resets" USING btree (email);
CREATE INDEX "password_resets_token_index" ON "public"."password_resets" USING btree (token);

-- ----------------------------
-- Uniques structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD UNIQUE ("email");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD PRIMARY KEY ("id");

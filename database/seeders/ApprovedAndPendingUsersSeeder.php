<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ApprovedAndPendingUsersSeeder extends Seeder
{
    public function run()
    {
        // PostgreSQL doesn't use FOREIGN_KEY_CHECKS
        DB::table('vendors')->truncate();
        DB::table('users')->truncate();

        $users = [
            // Admin user
            [
                'name' => 'System Admin',
                'email' => 'admin@autochain.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'admin',
                'status' => 'approved',
                'company' => 'AutoChain HQ',
                'phone' => '+1-555-0000',
                'address' => '1 Admin Plaza, HQ City',
                'profile_picture' => 'images/profile/admin.jpeg',
                'supporting_documents' => json_encode([]),
                'auto_visit_scheduled' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Vendors (20 approved, realistic details)
            [
                'name' => 'Ava Carter',
                'email' => 'ava.carter@vendortech.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorTech Solutions',
                'phone' => '+1-555-1101',
                'address' => '101 Vendor Lane, New York, NY',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['ava_carter_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Benjamin Lee',
                'email' => 'benjamin.lee@autovend.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'AutoVend Inc.',
                'phone' => '+1-555-1102',
                'address' => '202 Market St, Los Angeles, CA',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['benjamin_lee_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chloe Kim',
                'email' => 'chloe.kim@partsplus.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'PartsPlus',
                'phone' => '+1-555-1103',
                'address' => '303 Commerce Ave, Chicago, IL',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['chloe_kim_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David Patel',
                'email' => 'david.patel@vendormax.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorMax',
                'phone' => '+1-555-1104',
                'address' => '404 Vendor Blvd, Dallas, TX',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['david_patel_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ella Zhang',
                'email' => 'ella.zhang@autopartspro.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'AutoParts Pro',
                'phone' => '+1-555-1105',
                'address' => '505 Parts Ave, San Francisco, CA',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['ella_zhang_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Frank Müller',
                'email' => 'frank.muller@vendorexpress.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorExpress',
                'phone' => '+1-555-1106',
                'address' => '606 Express Rd, Miami, FL',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['frank_muller_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grace Park',
                'email' => 'grace.park@vendornet.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorNet',
                'phone' => '+1-555-1107',
                'address' => '707 Network St, Seattle, WA',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['grace_park_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Henry Kim',
                'email' => 'henry.kim@vendorglobal.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorGlobal',
                'phone' => '+1-555-1108',
                'address' => '808 Global Ave, Boston, MA',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['henry_kim_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Isabella Rossi',
                'email' => 'isabella.rossi@vendormarket.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorMarket',
                'phone' => '+1-555-1109',
                'address' => '909 Market St, Chicago, IL',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['isabella_rossi_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jack Wilson',
                'email' => 'jack.wilson@vendordirect.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorDirect',
                'phone' => '+1-555-1110',
                'address' => '1010 Direct Dr, Austin, TX',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['jack_wilson_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Katherine Lee',
                'email' => 'katherine.lee@vendornow.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorNow',
                'phone' => '+1-555-1111',
                'address' => '1111 Now St, Denver, CO',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['katherine_lee_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Liam Brown',
                'email' => 'liam.brown@vendorteam.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorTeam',
                'phone' => '+1-555-1112',
                'address' => '1212 Team Ave, San Diego, CA',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['liam_brown_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mia Garcia',
                'email' => 'mia.garcia@vendornetwork.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorNetwork',
                'phone' => '+1-555-1113',
                'address' => '1313 Network Blvd, Orlando, FL',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['mia_garcia_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Noah Smith',
                'email' => 'noah.smith@vendorgroup.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorGroup',
                'phone' => '+1-555-1114',
                'address' => '1414 Group St, Atlanta, GA',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['noah_smith_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Olivia Davis',
                'email' => 'olivia.davis@vendormarket.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorMarket',
                'phone' => '+1-555-1115',
                'address' => '1515 Market Ave, Charlotte, NC',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['olivia_davis_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paul Walker',
                'email' => 'paul.walker@vendorlink.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorLink',
                'phone' => '+1-555-1116',
                'address' => '1616 Link St, Portland, OR',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['paul_walker_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quinn Harris',
                'email' => 'quinn.harris@vendornet.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorNet',
                'phone' => '+1-555-1117',
                'address' => '1717 Net Ave, Minneapolis, MN',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['quinn_harris_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ryan Clark',
                'email' => 'ryan.clark@vendorconnect.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorConnect',
                'phone' => '+1-555-1118',
                'address' => '1818 Connect Blvd, Salt Lake City, UT',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['ryan_clark_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sophia Martinez',
                'email' => 'sophia.martinez@vendorplus.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorPlus',
                'phone' => '+1-555-1119',
                'address' => '1919 Plus St, San Antonio, TX',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['sophia_martinez_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tyler Nguyen',
                'email' => 'tyler.nguyen@vendortech.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'approved',
                'company' => 'VendorTech',
                'phone' => '+1-555-1120',
                'address' => '2020 Tech Ave, San Jose, CA',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['tyler_nguyen_doc1.pdf']),
                'auto_visit_scheduled' => true,
                'segment' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Manufacturers (20 approved, realistic details)
            [
    'name' => 'Daniel Smith',
    'email' => 'daniel.smith@manufactory.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'manufacturer',
    'status' => 'approved',
    'company' => 'Manufactory Ltd.',
    'phone' => '+1-555-1201',
    'address' => '401 Factory Rd, Detroit, MI',
    'profile_picture' => 'images/profile/manufacturer.jpeg',
    'supporting_documents' => json_encode(['daniel_smith_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => 'Ella Brown',
    'email' => 'ella.brown@automan.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'manufacturer',
    'status' => 'approved',
    'company' => 'AutoMan Corp.',
    'phone' => '+1-555-1202',
    'address' => '502 Assembly St, Houston, TX',
    'profile_picture' => 'images/profile/manufacturer.jpeg',
    'supporting_documents' => json_encode(['ella_brown_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => 'Frank Wilson',
    'email' => 'frank.wilson@buildit.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'manufacturer',
    'status' => 'approved',
    'company' => 'BuildIt Manufacturing',
    'phone' => '+1-555-1203',
    'address' => '603 Industrial Ave, Seattle, WA',
    'profile_picture' => 'images/profile/manufacturer.jpeg',
    'supporting_documents' => json_encode(['frank_wilson_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => 'Grace Lee',
    'email' => 'grace.lee@manuworks.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'manufacturer',
    'status' => 'approved',
    'company' => 'ManuWorks',
    'phone' => '+1-555-1204',
    'address' => '704 Works Blvd, San Jose, CA',
    'profile_picture' => 'images/profile/manufacturer.jpeg',
    'supporting_documents' => json_encode(['grace_lee_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => 'Henry Adams',
    'email' => 'henry.adams@autoforge.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'manufacturer',
    'status' => 'approved',
    'company' => 'AutoForge',
    'phone' => '+1-555-1205',
    'address' => '805 Forge St, Boston, MA',
    'profile_picture' => 'images/profile/manufacturer.jpeg',
    'supporting_documents' => json_encode(['henry_adams_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],

            // Suppliers (20 approved, realistic details)
            [
    'name' => 'Alice Morgan',
    'email' => 'alice.morgan@supplyco.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'supplier',
    'status' => 'approved',
    'company' => 'SupplyCo',
    'phone' => '+1-555-1301',
    'address' => '301 Supply Blvd, Dallas, TX',
    'profile_picture' => 'images/profile/supplier.jpeg',
    'supporting_documents' => json_encode(['alice_morgan_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => 'Brian Lee',
    'email' => 'brian.lee@rawsource.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'supplier',
    'status' => 'approved',
    'company' => 'RawSource',
    'phone' => '+1-555-1302',
    'address' => '302 Source St, Houston, TX',
    'profile_picture' => 'images/profile/supplier.jpeg',
    'supporting_documents' => json_encode(['brian_lee_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => 'Carla Diaz',
    'email' => 'carla.diaz@partsupply.com',
    'password' => Hash::make('autochainnexus'),
    'role' => 'supplier',
    'status' => 'approved',
    'company' => 'PartSupply',
    'phone' => '+1-555-1303',
    'address' => '303 Parts Ave, Miami, FL',
    'profile_picture' => 'images/profile/supplier.jpeg',
    'supporting_documents' => json_encode(['carla_diaz_doc1.pdf']),
    'auto_visit_scheduled' => null,
    'created_at' => now(),
    'updated_at' => now(),
],


            // Retailers (20 approved, realistic details)
            [
    
        'name' => 'Olivia Turner',
        'email' => 'olivia.turner@retailmart.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'RetailMart',
        'phone' => '+1-555-1401',
        'address' => '201 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['olivia_turner_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Ethan Brooks',
        'email' => 'ethan.brooks@shopwise.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'ShopWise',
        'phone' => '+1-555-1402',
        'address' => '202 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['ethan_brooks_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Sophia Bennett',
        'email' => 'sophia.bennett@urbanstore.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'UrbanStore',
        'phone' => '+1-555-1403',
        'address' => '203 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['sophia_bennett_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Liam Murphy',
        'email' => 'liam.murphy@quickmart.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'QuickMart',
        'phone' => '+1-555-1404',
        'address' => '204 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['liam_murphy_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Mia Collins',
        'email' => 'mia.collins@superstore.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'SuperStore',
        'phone' => '+1-555-1405',
        'address' => '205 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['mia_collins_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Noah Reed',
        'email' => 'noah.reed@marketplace.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'MarketPlace',
        'phone' => '+1-555-1406',
        'address' => '206 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['noah_reed_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Charlotte Hayes',
        'email' => 'charlotte.hayes@retailplus.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'RetailPlus',
        'phone' => '+1-555-1407',
        'address' => '207 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['charlotte_hayes_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Lucas Perry',
        'email' => 'lucas.perry@shopcity.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'ShopCity',
        'phone' => '+1-555-1408',
        'address' => '208 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['lucas_perry_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Amelia Foster',
        'email' => 'amelia.foster@retailzone.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'RetailZone',
        'phone' => '+1-555-1409',
        'address' => '209 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['amelia_foster_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Mason Gray',
        'email' => 'mason.gray@shopmart.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'ShopMart',
        'phone' => '+1-555-1410',
        'address' => '210 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['mason_gray_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Ella Price',
        'email' => 'ella.price@retailworld.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'RetailWorld',
        'phone' => '+1-555-1411',
        'address' => '211 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['ella_price_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Logan Bell',
        'email' => 'logan.bell@shopwise.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'ShopWise',
        'phone' => '+1-555-1412',
        'address' => '212 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['logan_bell_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Harper Evans',
        'email' => 'harper.evans@retailmart.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'RetailMart',
        'phone' => '+1-555-1413',
        'address' => '213 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['harper_evans_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Benjamin Scott',
        'email' => 'benjamin.scott@urbanstore.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'UrbanStore',
        'phone' => '+1-555-1414',
        'address' => '214 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['benjamin_scott_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Emily Adams',
        'email' => 'emily.adams@quickmart.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'QuickMart',
        'phone' => '+1-555-1415',
        'address' => '215 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['emily_adams_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Jack Morris',
        'email' => 'jack.morris@superstore.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'SuperStore',
        'phone' => '+1-555-1416',
        'address' => '216 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['jack_morris_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Grace Walker',
        'email' => 'grace.walker@marketplace.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'MarketPlace',
        'phone' => '+1-555-1417',
        'address' => '217 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['grace_walker_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Henry King',
        'email' => 'henry.king@retailplus.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'RetailPlus',
        'phone' => '+1-555-1418',
        'address' => '218 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['henry_king_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'Zoe Cooper',
        'email' => 'zoe.cooper@shopcity.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'ShopCity',
        'phone' => '+1-555-1419',
        'address' => '219 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['zoe_cooper_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],
    [
        'name' => 'William Hughes',
        'email' => 'william.hughes@retailzone.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'retailer',
        'status' => 'approved',
        'company' => 'RetailZone',
        'phone' => '+1-555-1420',
        'address' => '220 Retail Ave, Miami, FL',
        'profile_picture' => 'images/profile/retailer.jpeg',
        'supporting_documents' => json_encode(['william_hughes_doc1.pdf']),
        'auto_visit_scheduled' => null,
    ],


            // Analysts (20 approved, realistic details)
            
    [
        'name' => 'Samuel Carter',
        'email' => 'samuel.carter@analytica.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'Analytica Insights',
        'phone' => '+1-555-1501',
        'address' => '101 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['samuel_carter_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Victoria Lee',
        'email' => 'victoria.lee@datawise.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'DataWise Analytics',
        'phone' => '+1-555-1502',
        'address' => '102 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['victoria_lee_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Matthew Kim',
        'email' => 'matthew.kim@insightpro.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'InsightPro',
        'phone' => '+1-555-1503',
        'address' => '103 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['matthew_kim_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Chloe Adams',
        'email' => 'chloe.adams@trendlytics.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'TrendLytics',
        'phone' => '+1-555-1504',
        'address' => '104 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['chloe_adams_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Ryan Foster',
        'email' => 'ryan.foster@marketintel.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'MarketIntel',
        'phone' => '+1-555-1505',
        'address' => '105 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['ryan_foster_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Ella Brooks',
        'email' => 'ella.brooks@analytix.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'Analytix Group',
        'phone' => '+1-555-1506',
        'address' => '106 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['ella_brooks_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'David Green',
        'email' => 'david.green@insightful.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'Insightful Analytics',
        'phone' => '+1-555-1507',
        'address' => '107 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['david_green_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Sofia Turner',
        'email' => 'sofia.turner@datanova.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'DataNova',
        'phone' => '+1-555-1508',
        'address' => '108 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['sofia_turner_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Jackson Lee',
        'email' => 'jackson.lee@marketmetrics.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'MarketMetrics',
        'phone' => '+1-555-1509',
        'address' => '109 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['jackson_lee_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Grace Evans',
        'email' => 'grace.evans@trendwise.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'TrendWise',
        'phone' => '+1-555-1510',
        'address' => '110 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['grace_evans_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Benjamin Scott',
        'email' => 'benjamin.scott@insightedge.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'InsightEdge',
        'phone' => '+1-555-1511',
        'address' => '111 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['benjamin_scott_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Lily Parker',
        'email' => 'lily.parker@datanalytics.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'DataNaltyics',
        'phone' => '+1-555-1512',
        'address' => '112 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['lily_parker_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Mason Rivera',
        'email' => 'mason.rivera@marketinsight.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'MarketInsight',
        'phone' => '+1-555-1513',
        'address' => '113 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['mason_rivera_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Avery Morgan',
        'email' => 'avery.morgan@analytica.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'Analytica Insights',
        'phone' => '+1-555-1514',
        'address' => '114 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['avery_morgan_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Logan Bailey',
        'email' => 'logan.bailey@datawise.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'DataWise Analytics',
        'phone' => '+1-555-1515',
        'address' => '115 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['logan_bailey_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Scarlett Reed',
        'email' => 'scarlett.reed@insightpro.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'InsightPro',
        'phone' => '+1-555-1516',
        'address' => '116 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['scarlett_reed_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Elijah Cooper',
        'email' => 'elijah.cooper@trendlytics.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'TrendLytics',
        'phone' => '+1-555-1517',
        'address' => '117 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['elijah_cooper_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Penelope Ross',
        'email' => 'penelope.ross@marketintel.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'MarketIntel',
        'phone' => '+1-555-1518',
        'address' => '118 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['penelope_ross_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Carter James',
        'email' => 'carter.james@analytix.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'Analytix Group',
        'phone' => '+1-555-1519',
        'address' => '119 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['carter_james_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],
    [
        'name' => 'Layla Bennett',
        'email' => 'layla.bennett@insightful.com',
        'password' => Hash::make('autochainnexus'),
        'role' => 'analyst',
        'status' => 'approved',
        'company' => 'Insightful Analytics',
        'phone' => '+1-555-1520',
        'address' => '120 Data Lane, Boston, MA',
        'profile_picture' => 'images/profile/analyst.jpeg',
        'supporting_documents' => json_encode(['layla_bennett_doc1.pdf']),
        'auto_visit_scheduled' => null
    ],



            //Pending users
            [
                'name' => 'Natalie Simmons',
                'email' => 'natalie.simmons@vendortech.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'pending',
                'company' => 'VendorTech Solutions',
                'phone' => '+1-555-1601',
                'address' => '301 Vendor Lane, New York, NY',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['natalie_simmons_doc1.pdf']),
                'auto_visit_scheduled' => false
            ],
            [
                'name' => 'Derek Foster',
                'email' => 'derek.foster@vendormax.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'vendor',
                'status' => 'pending',
                'company' => 'VendorMax',
                'phone' => '+1-555-1602',
                'address' => '302 Vendor Lane, New York, NY',
                'profile_picture' => 'images/profile/vendor.jpeg',
                'supporting_documents' => json_encode(['derek_foster_doc1.pdf']),
                'auto_visit_scheduled' => false
            ],
            [
                'name' => 'Isabella Grant',
                'email' => 'isabella.grant@manufactory.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'manufacturer',
                'status' => 'pending',
                'company' => 'Manufactory Ltd.',
                'phone' => '+1-555-1701',
                'address' => '501 Factory Rd, Detroit, MI',
                'profile_picture' => 'images/profile/manufacturer.jpeg',
                'supporting_documents' => json_encode(['isabella_grant_doc1.pdf']),
                'auto_visit_scheduled' => null
            ],
            [
                'name' => 'Owen Parker',
                'email' => 'owen.parker@supplyco.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'supplier',
                'status' => 'pending',
                'company' => 'SupplyCo',
                'phone' => '+1-555-1801',
                'address' => '601 Supply Blvd, Dallas, TX',
                'profile_picture' => 'images/profile/supplier.jpeg',
                'supporting_documents' => json_encode(['owen_parker_doc1.pdf']),
                'auto_visit_scheduled' => null
            ],
            [
                'name' => 'Megan Lewis',
                'email' => 'shakirannannyombi@gmail.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'retailer',
                'status' => 'pending',
                'company' => 'RetailMart',
                'phone' => '+1-555-1901',
                'address' => '701 Retail Ave, Miami, FL',
                'profile_picture' => 'images/profile/retailer.jpeg',
                'supporting_documents' => json_encode(['megan_lewis_doc1.pdf']),
                'auto_visit_scheduled' => null
            ],
            [
                'name' => 'Evan Mitchell',
                'email' => 'evan.mitchell@analytica.com',
                'password' => Hash::make('autochainnexus'),
                'role' => 'analyst',
                'status' => 'pending',
                'company' => 'Analytica Insights',
                'phone' => '+1-555-2001',
                'address' => '801 Data Lane, Boston, MA',
                'profile_picture' => 'images/profile/analyst.jpeg',
                'supporting_documents' => json_encode(['evan_mitchell_doc1.pdf']),
                'auto_visit_scheduled' => null
            ],
            ];

        // Ensure all users have created_at and updated_at
        foreach ($users as &$user) {
            if (!isset($user['created_at'])) {
                $user['created_at'] = now();
            }
            if (!isset($user['updated_at'])) {
                $user['updated_at'] = now();
            }
            // Only vendors keep their supporting_documents, others get an empty array
            if (!isset($user['role']) || $user['role'] !== 'vendor') {
                $user['supporting_documents'] = json_encode([]);
            }
        }
        unset($user);

        $users = array_map(function($user) {
            return [
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => null,
                'password' => $user['password'],
                'role' => $user['role'],
                'status' => $user['status'],
                'company' => $user['company'],
                'phone' => $user['phone'],
                'address' => $user['address'],
                'profile_picture' => $user['profile_picture'],
                'supporting_documents' => $user['supporting_documents'],
                'auto_visit_scheduled' => $user['auto_visit_scheduled'],
                // Set segment to 'A' for vendors, otherwise null
                'segment' => ($user['role'] === 'vendor') ? ($user['segment'] ?? 'A') : null,
                'remember_token' => null,
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ];
        }, $users);

        DB::table('users')->insert($users);

        // Insert into vendors table for all approved vendors
        $approvedVendors = DB::table('users')->where('role', 'vendor')->where('status', 'approved')->get();
        $carImages = [
            'images/car1.png',
            'images/car2.png',
            'images/car3.png',
            'images/car4.png',
            'images/car5.png',
            'images/car6.png',
            'images/car7.png',
            'images/car8.png',
            'images/car9.png',
            'images/car10.png',
        ];
        $productIndex = 0;
        foreach ($approvedVendors as $user) {
    if (is_null($user->segment)) {
                continue;
    } else {
        $segment = $user->segment;
    }
            DB::table('vendors')->insert([
    'user_id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
    'phone' => $user->phone,
    'password' => $user->password,
    'company' => $user->company,
    'address' => $user->address,
    'profile_picture' => $user->profile_picture,
    'supporting_documents' => $user->supporting_documents,
    'vendor_license' => null,
    'product_categories' => null,
    'service_areas' => null,
    'contract_terms' => null,
    'segment' => $segment,
    'segment_name' => null,
    'total_orders' => 0,
    'total_quantity' => 0,
    'total_value' => null,
    'most_ordered_product' => null,
    'order_frequency' => 0,
    'fulfillment_rate' => 0,
    'cancellation_rate' => 0,
    'created_at' => now(),
    'updated_at' => now(),
]);
        }

        // Clean related tables before inserting
        DB::table('retailer_orders')->delete();
        DB::table('retailer_sales')->delete();
        DB::table('retailers')->delete();
        // Insert into retailers table for all approved retailers
        $approvedRetailers = DB::table('users')->where('role', 'retailer')->where('status', 'approved')->get();
        $storeLocations = [
            '201 Retail Ave, Miami, FL',
            '202 Retail Ave, Miami, FL',
            '203 Retail Ave, Miami, FL',
            '204 Retail Ave, Miami, FL',
            '205 Retail Ave, Miami, FL',
        ];
        $productInventories = [
            '[{"product": "Toyota Corolla 2024", "qty": 5}, {"product": "Honda Civic 2024", "qty": 3}]',
            '[{"product": "Ford F-150 2024", "qty": 2}, {"product": "BMW 3 Series 2024", "qty": 1}]',
            '[{"product": "Mazda 3 2024", "qty": 4}, {"product": "Kia K5 2024", "qty": 2}]',
        ];
        $businessHours = [
            'Mon-Fri 9am-6pm',
            'Mon-Sat 8am-8pm',
            'Everyday 10am-7pm',
        ];
        $retailLicenses = [
            'RL-2024-001', 'RL-2024-002', 'RL-2024-003', 'RL-2024-004', 'RL-2024-005'
        ];
        $carModels = [
            'Toyota Corolla 2024', 'Honda Civic 2024', 'Ford F-150 2024', 'BMW 3 Series 2024',
            'Mercedes-Benz C-Class 2024', 'Audi A4 2024', 'Volkswagen Golf 2024',
            'Hyundai Sonata 2024', 'Kia K5 2024', 'Mazda 3 2024'
        ];
        foreach ($approvedRetailers as $i => $user) {
            $storeLoc = $storeLocations[$i % count($storeLocations)];
            $prodInv = $productInventories[$i % count($productInventories)];
            $bizHours = $businessHours[$i % count($businessHours)];
            $license = $retailLicenses[$i % count($retailLicenses)];
            DB::table('retailers')->insert([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '+1-555-0000',
                'password' => $user->password,
                'company' => $user->company ?? 'RetailMart',
                'address' => $user->address ?? $storeLoc,
                'profile_picture' => $user->profile_picture ?? 'images/profile/retailer.jpeg',
                'supporting_documents' => $user->supporting_documents ?? json_encode([]),
                'retail_license' => $license,
                'store_locations' => $storeLoc,
                'product_inventory' => $prodInv,
                'business_hours' => $bizHours,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Create varied orders for each retailer
            $orders = [];
            $productCounts = [];
            // Heavy buyer: buys a lot of one product
            if ($i % 5 == 0) {
                $heavyProduct = $carModels[array_rand($carModels)];
                $numOrders = rand(6, 12);
                for ($j = 0; $j < $numOrders; $j++) {
                    $quantity = rand(5, 15);
                    $totalAmount = $quantity * rand(30000, 90000);
                    $status = $j % 3 == 0 ? 'confirmed' : ($j % 3 == 1 ? 'shipped' : 'delivered');
                    $orders[] = [
                        'user_id' => $user->id,
                        'vendor_id' => 1,
                        'customer_name' => 'Heavy Buyer Customer ' . ($j + 1),
                        'car_model' => $heavyProduct,
                        'quantity' => $quantity,
                        'status' => $status,
                        'total_amount' => $totalAmount,
                        'ordered_at' => now()->subDays(rand(1, 30)),
                        'confirmed_at' => now()->subDays(rand(1, 29)),
                        'shipped_at' => now()->subDays(rand(1, 28)),
                        'delivered_at' => $status === 'delivered' ? now()->subDays(rand(1, 27)) : null,
                        'notes' => 'Heavy buyer order',
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 28)),
                    ];
                    if (!isset($productCounts[$heavyProduct])) $productCounts[$heavyProduct] = 0;
                    $productCounts[$heavyProduct] += $quantity;
                }
            // Niche buyer: buys only 1-2 products repeatedly
            } else if ($i % 5 == 1) {
                $nicheProducts = array_rand(array_flip($carModels), 2);
                $numOrders = rand(4, 8);
                for ($j = 0; $j < $numOrders; $j++) {
                    $carModel = $nicheProducts[array_rand($nicheProducts)];
                    $quantity = rand(2, 6);
                    $totalAmount = $quantity * rand(25000, 70000);
                    $status = $j % 2 == 0 ? 'confirmed' : 'shipped';
                    $orders[] = [
                        'user_id' => $user->id,
                        'vendor_id' => 1,
                        'customer_name' => 'Niche Buyer Customer ' . ($j + 1),
                        'car_model' => $carModel,
                        'quantity' => $quantity,
                        'status' => $status,
                        'total_amount' => $totalAmount,
                        'ordered_at' => now()->subDays(rand(1, 30)),
                        'confirmed_at' => now()->subDays(rand(1, 29)),
                        'shipped_at' => now()->subDays(rand(1, 28)),
                        'delivered_at' => $status === 'delivered' ? now()->subDays(rand(1, 27)) : null,
                        'notes' => 'Niche buyer order',
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 28)),
                    ];
                    if (!isset($productCounts[$carModel])) $productCounts[$carModel] = 0;
                    $productCounts[$carModel] += $quantity;
                }
            // Random buyers: random products, quantities, and order counts
            } else {
                $numOrders = rand(2, 10);
                for ($j = 0; $j < $numOrders; $j++) {
                    $carModel = $carModels[array_rand($carModels)];
                    $quantity = rand(1, 8);
                    $totalAmount = $quantity * rand(20000, 80000);
                    $status = $j % 3 == 0 ? 'confirmed' : ($j % 3 == 1 ? 'shipped' : 'delivered');
                    $orders[] = [
                        'user_id' => $user->id,
                        'vendor_id' => 1,
                        'customer_name' => 'Random Buyer Customer ' . ($j + 1),
                        'car_model' => $carModel,
                        'quantity' => $quantity,
                        'status' => $status,
                        'total_amount' => $totalAmount,
                        'ordered_at' => now()->subDays(rand(1, 30)),
                        'confirmed_at' => now()->subDays(rand(1, 29)),
                        'shipped_at' => now()->subDays(rand(1, 28)),
                        'delivered_at' => $status === 'delivered' ? now()->subDays(rand(1, 27)) : null,
                        'notes' => 'Random buyer order',
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 28)),
                    ];
                    if (!isset($productCounts[$carModel])) $productCounts[$carModel] = 0;
                    $productCounts[$carModel] += $quantity;
                }
            }
            DB::table('retailer_orders')->insert($orders);
            $mostBought = array_keys($productCounts, max($productCounts))[0];
            // You can update the retailer record or output this info as needed
        }

        // Attach sample documents to pending users
        $pendingUsers = DB::table('users')->where('status', 'pending')->get();
        $userDocs = [
            // Natalie Simmons (vendor, should pass)
            'natalie.simmons@vendortech.com' => [
                ['file_path' => 'natalie_simmons_financial_statement_2023.pdf', 'document_type' => 'financials'],
                ['file_path' => 'natalie_simmons_iso_certificate.docx', 'document_type' => 'compliance'],
                ['file_path' => 'natalie_simmons_business_license.jpeg', 'document_type' => 'license'],
            ],
            // Derek Foster (vendor, should fail)
            'derek.foster@vendormax.com' => [
                ['file_path' => 'derek_foster_financial_statement_2023.pdf', 'document_type' => 'financials'],
                ['file_path' => 'derek_foster_iso_27001_certificate.jpg', 'document_type' => 'compliance'],
                ['file_path' => 'derek_foster_business_license.docx', 'document_type' => 'license'],
            ],
            // Owen Parker (supplier)
            'owen.parker@supplyco.com' => [
                ['file_path' => 'owen_parker_supplier_registration.pdf', 'document_type' => 'registration'],
                ['file_path' => 'owen_parker_trade_references.jpeg', 'document_type' => 'references'],
                ['file_path' => 'owen_parker_logistics_capabilities.docx', 'document_type' => 'logistics'],
            ],
            // Megan Lewis (retailer)
            'megan.lewis@retailmart.com' => [
                ['file_path' => 'megan_lewis_retail_operating_license.pdf', 'document_type' => 'license'],
                ['file_path' => 'megan_lewis_store_network_overview.docx', 'document_type' => 'overview'],
                ['file_path' => 'megan_lewis_customer_service_policy.png', 'document_type' => 'policy'],
            ],
            // Evan Mitchell (analyst)
            'evan.mitchell@analytica.com' => [
                ['file_path' => 'evan_mitchell_cv.pdf', 'document_type' => 'cv'],
                ['file_path' => 'evan_mitchell_professional_certifications.jpeg', 'document_type' => 'certifications'],
                ['file_path' => 'evan_mitchell_sample_report_excerpt.docx', 'document_type' => 'report'],
            ],
        ];
        foreach ($pendingUsers as $user) {
            if ($user->role === 'vendor' && isset($userDocs[$user->email])) {
                foreach ($userDocs[$user->email] as $doc) {
                    \App\Models\UserDocument::create([
                        'user_id' => $user->id,
                        'file_path' => $doc['file_path'],
                        'document_type' => $doc['document_type'],
                    ]);
                }
            }
        }

        // Add demo analyst reports for various dates with realistic titles
        DB::table('analyst_reports')->truncate();
        $reportTitles = [
            'Q2 Sales Analysis',
            'Inventory Status July',
            'Supplier Performance Review',
            'Monthly Sales Breakdown',
            'Stock Turnover Report',
            'Vendor Fulfillment Analysis',
            'Sales Growth Trends',
            'Inventory Aging Report',
            'Supplier On-Time Delivery',
            'Regional Sales Comparison',
            'Critical Stock Alert',
            'Vendor Order Accuracy',
            'Sales by Product Line',
            'Inventory Valuation',
            'Supplier Quality Assessment',
        ];
        $reportTypes = ['sales', 'inventory', 'performance'];
        $targetRoles = ['retailer', 'manufacturer', 'supplier'];
        $baseDate = now()->subDays(30);
        for ($i = 0; $i < 15; $i++) {
            DB::table('analyst_reports')->insert([
                'title' => $reportTitles[$i],
                'type' => $reportTypes[$i % 3],
                'target_role' => $targetRoles[$i % 3],
                'summary' => 'This is a sample report for ' . $reportTitles[$i] . '.',
                'report_file' => null,
                'created_at' => $baseDate->copy()->addDays($i * 2),
                'updated_at' => $baseDate->copy()->addDays($i * 2),
            ]);
        }
    }
} 
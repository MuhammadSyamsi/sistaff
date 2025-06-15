<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/keuangan', 'Page::keuangan');
$routes->get('/mutasi', 'Page::mutasi');
$routes->get('/santridata', 'Santri::data');
$routes->get('/lulus', 'Santri::lulus');
$routes->get('/santripsb', 'Santri::psb');
$routes->get('/santrialumni', 'Santri::alumni');
$routes->post('/pindah(:any)', 'Santri::pindah/$1');
$routes->post('/mig(:any)', 'Santri::mig/$1');
$routes->get('/santrilain', 'Santri::lain');
$routes->get('/koperasi', 'Koperasi');
$routes->get('/tambah', 'Page::tambah');
$routes->get('/pembayaranalumni', 'Alumni::tambah');
$routes->post('/save', 'Page::save');
$routes->post('/savealumni', 'Alumni::save');
$routes->post('/cetak', 'Page::cetak');
$routes->post('/cetakpsb', 'Psb::cetak');
$routes->post('/cetakalumni', 'Alumni::cetak');
$routes->post('/mutasi', 'Page::mutasi');
$routes->post('/nextmonth', 'Page::nextmonth');
$routes->post('/naikkelas', 'Page::naikkelas');
$routes->post('/psb(:num)', 'Psb::dtransaksi/$1');
$routes->post('/(:num)', 'Page::dtransaksi/$1');
$routes->post('/edit', 'Page::edit');
$routes->post('/delet/(:num)', 'Page::delet/$1');
$routes->get('/keuangan/tunggakan', 'Page::tunggakan');
$routes->get('/alumni/tunggakan', 'Alumni::tunggakan');
$routes->get('/tunggakan', 'Page::datatunggakan');
$routes->post('/tunggakan', 'Page::datatunggakan');
$routes->get('/keuangan/transaksi', 'Page::transaksi');
$routes->get('/keuangan/keterangan', 'Page::keterangan');
$routes->get('/downloadpsb', 'Psb::laporanpsb');
$routes->get('/psb', 'Psb');
$routes->get('/psbmts', 'Psb::mts');
$routes->get('/psbma', 'Psb::ma');
$routes->get('/tambahpsb', 'Psb::tambah');
$routes->post('/editformulir(:num)', 'Psb::editformulir/$1');
$routes->post('/mundur(:num)', 'Psb::mundur/$1');

$routes->get('/pembayaran', 'Home::pembayaran');
$routes->get('pembayaran/santri-(:any)', 'Home::bayar_santri/$1');
$routes->get('filter-santri', 'Home::filterSantri');

$routes->get('/migrasi', 'Psb::migrasi');
$routes->post('/formulir_psb', 'Psb::save');
$routes->post('/bayar', 'Psb::bayar');
$routes->post('/edittungpsb', 'Psb::edittung');
$routes->post('/formulir(:num)', 'Psb::formulir/$1');
$routes->post('/fullform', 'Psb::fullform');
$routes->post('/fulleditform', 'Psb::fulleditform');
$routes->post('/daftarbaru_psb', 'Psb::daftarbaru_psb');
$routes->post('/komitmen(:num)', 'Psb::komitmen/$1');
$routes->post('/closing(:num)', 'Psb::closing/$1');
$routes->post('/pembayaran', 'Psb::pembayaran');

$routes->resource('api/home', ['controller' => 'Api\Home']);
$routes->resource('api/kedua', ['controller' => 'Api\Kedua']);
$routes->resource('api/psb', ['controller' => 'Api\Psb']);
$routes->resource('api/alumni', ['controller' => 'Api\Alumni']);

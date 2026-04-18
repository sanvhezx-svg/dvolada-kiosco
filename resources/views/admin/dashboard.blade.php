<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — D'Volada</title>
    <style>
        @extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Inicio / Dashboard')

@section('content')
<style>
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .card {
        background: #111;
        border: 1px solid #1a1a1a;
        border-radius: 16px;
        padding: 28px;
        text-align: center;
    }
    .card .icon { font-size: 2rem; margin-bottom: 12px; }
    .card h3 { color: #FFB400; font-size: 1.8rem; margin-bottom: 4px; }
    .card p { color: #555; font-size: 0.85rem; }
</style>

<div class="cards">
    <div class="card">
        <div class="icon">🍽️</div>
        <h3>0</h3>
        <p>Productos</p>
    </div>
    <div class="card">
        <div class="icon">📋</div>
        <h3>0</h3>
        <p>Órdenes hoy</p>
    </div>
    <div class="card">
        <div class="icon">⭐</div>
        <h3>0</h3>
        <p>Clientes VIP</p>
    </div>
    <div class="card">
        <div class="icon">💰</div>
        <h3>$0</h3>
        <p>Ventas hoy</p>
    </div>
</div>
@endsection
</body>
</html>
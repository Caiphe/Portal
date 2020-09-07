@extends('layouts.admin')

@section('title', 'FAQs')

@section('content')
    <a href="{{ route('admin.faq.create') }}" class="button mb-2">Create FAQ</a>
    <x-admin.table :collection="$faqs" model-name="faq" :fields="['question', 'category.title']"></x-admin.table>
@endsection
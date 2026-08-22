@extends('layouts.web')


@section('title', $pageTitle . ' | Government Jobs & Updates')


@section('meta_description',
$metaDescription
?? ($pageTitle . ' - Read important information, policies and details about our government jobs website.')
)


@section('meta_keywords',
$metaKeywords
?? strtolower($pageTitle . ', government jobs, govt jobs, recruitment')
)


@section('canonical', url($pageUrl))


@section('og_title', $pageTitle)


@section(
'og_description',
$metaDescription
?? ($pageTitle . ' - Government Jobs & Updates')
)


@section('content')

<div class="bg-light py-4">

    <div class="container">


        {{-- BREADCRUMB --}}

        <nav aria-label="breadcrumb" class="mb-3">

            <ol class="breadcrumb mb-0">

                <li class="breadcrumb-item">

                    <a href="{{ url('/') }}"
                        class="text-decoration-none">

                        Home

                    </a>

                </li>


                <li class="breadcrumb-item active"
                    aria-current="page">

                    {{ $pageTitle }}

                </li>

            </ol>

        </nav>



        {{-- PAGE CONTENT --}}

        <article class="bg-white border rounded-2 shadow-sm">


            {{-- HEADER --}}

            <div class="p-3 p-md-4 border-bottom">


                <h1 class="h2 fw-bold text-dark mb-0">

                    {{ $pageTitle }}

                </h1>


            </div>



            {{-- CONTENT --}}

            <div class="p-3 p-md-4">


                <div class="static-page-content">

                    {!! $content !!}

                </div>


            </div>


        </article>


    </div>

</div>

@endsection
{{-- =========================================================
     COMMON WEBSITE SIDEBAR
========================================================= --}}


{{-- =========================================================
     SEARCH JOBS
========================================================= --}}

<section class="bg-white border rounded-2 shadow-sm mb-4">

    <div
        class="px-3 py-3 text-white"
        style="background:#06245f;">

        <h2 class="h6 fw-bold mb-0">
            Search Jobs
        </h2>

    </div>


    <div class="p-3">

        <form
            action="{{ url('/search') }}"
            method="GET">

            <label
                for="sidebar-search"
                class="visually-hidden">

                Search government jobs

            </label>


            <div class="input-group">

                <input
                    type="search"
                    id="sidebar-search"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-control"
                    placeholder="Search jobs..."
                    autocomplete="off">


                <button
                    type="submit"
                    class="btn"
                    aria-label="Search jobs"
                    style="background:#06245f;color:#fff;">

                    Search

                </button>

            </div>

        </form>

    </div>

</section>



{{-- =========================================================
     POPULAR CATEGORIES
========================================================= --}}

<section class="bg-white border rounded-2 shadow-sm mb-4">

    <div
        class="px-3 py-3 text-white"
        style="background:#06245f;">

        <h2 class="h6 fw-bold mb-0">
            Popular Categories
        </h2>

    </div>


    <div class="list-group list-group-flush">

        @forelse($sidebarCategories as $sidebarCategory)

            <a
                href="{{ route(
                    'category',
                    $sidebarCategory->slug
                ) }}"
                class="list-group-item
                       list-group-item-action
                       py-3">

                {{ $sidebarCategory->name }}

            </a>

        @empty

            <div class="p-3 text-muted">

                No categories available.

            </div>

        @endforelse

    </div>

</section>



{{-- =========================================================
     ALL CATEGORIES
========================================================= --}}

<section class="bg-white border rounded-2 shadow-sm mb-4">

    <div
        class="px-3 py-3 text-white"
        style="background:#06245f;">

        <h2 class="h6 fw-bold mb-0">
            All Categories
        </h2>

    </div>


    <div class="p-3">

        <div class="sidebar-category-tags">


            @foreach($sidebarCategories as $sidebarCategory)


                {{-- Parent Category --}}

                <a
                    href="{{ route(
                        'category',
                        $sidebarCategory->slug
                    ) }}"
                    class="sidebar-category-tag">

                    {{ $sidebarCategory->name }}

                </a>



                {{-- Sub Categories --}}

                @foreach(
                    $sidebarCategory->children
                    as $subCategory
                )

                    @if($subCategory->status)

                        <a
                            href="{{ route(
                                'category',
                                $subCategory->slug
                            ) }}"
                            class="sidebar-category-tag">

                            {{ $subCategory->name }}

                        </a>

                    @endif

                @endforeach


            @endforeach


        </div>

    </div>

</section>



{{-- =========================================================
     LATEST JOB UPDATES
========================================================= --}}

<section class="bg-white border rounded-2 shadow-sm mb-4">

    <div
        class="px-3 py-3 text-white"
        style="background:#06245f;">

        <h2 class="h6 fw-bold mb-0">
            Latest Job Updates
        </h2>

    </div>


    <div class="list-group list-group-flush">


        @forelse($sidebarLatestPosts as $latestPost)


            <a
                href="{{ route(
                    'post',
                    $latestPost->slug
                ) }}"
                class="list-group-item
                       list-group-item-action">


                <div class="fw-semibold">

                    {{ $latestPost->title }}

                </div>


                @if($latestPost->published_at)

                    <small class="text-muted">

                        {{ $latestPost->published_at->format(
                            'd M Y'
                        ) }}

                    </small>

                @endif


            </a>


        @empty


            <div class="p-3 text-muted">

                No latest updates available.

            </div>


        @endforelse


    </div>


    <div class="p-3 border-top">

        <a
            href="{{ route('latest.jobs') }}"
            class="btn btn-sm btn-primary w-100">

            View All Jobs →

        </a>

    </div>

</section>
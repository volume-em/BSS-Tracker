<x-filament-panels::page class="fi-dashboard-page">
    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="$this->getWidgetData()"
        :widgets="$this->getVisibleWidgets()"
    />

    @if(env('APP_ENV') === 'local')
        <script src="/js/vue.global.js"></script>
    @else
        <script src="/js/vue.global.prod.js"></script>
    @endif

    <script src="/js/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <h2 class="mb-5">Tree View</h2>

        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Select Investigator
        </span>

        <div id="vueApp">
            <select @change="handleInvestigatorChange" class="fi-select-input select-investigator mt-3 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 py-1.5 pe-8 text-base text-gray-950 transition duration-75 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] dark:text-white dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 [&amp;_optgroup]:bg-white [&amp;_optgroup]:dark:bg-gray-900 [&amp;_option]:bg-white [&amp;_option]:dark:bg-gray-900 ps-3" wire:model.live="tableRecordsPerPage">
                <option value="0">Select Investigator</option>

                @foreach ($data->pluck('name', 'id') as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

            <div class="mt-3" v-if="selectedInvestigator">
                <div class="flex flex-col border-gray-300 dark:border-gray-600 rounded-xl py-3 px-4 text-sm">
                    <p class="text-base">Projects</p>

                    <div class="grid grid-cols-3 mt-3">
                        <div class="row-auto col-span-5">
                            <div class="grid grid-cols-5 border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-800">
                                <p class="border-r border-gray-300 dark:border-gray-600 p-2">UID</p>
                                <p class="border-r border-gray-300 dark:border-gray-600 p-2">Name</p>
                                <p class="border-r border-gray-300 dark:border-gray-600 p-2">Created At</p>
                                <p class="border-r border-gray-300 dark:border-gray-600 p-2">Updated At</p>
                                <p class="border-r border-gray-300 dark:border-gray-600 p-2"></p>
                            </div>
                        </div>

                        <div v-if="selectedInvestigator.projects.length === 0" class="row-auto col-span-full border border-gray-300 dark:border-gray-600 p-2 border-t-0">
                            <p>This investigator has no assigned projects.</p>
                        </div>

                        <template v-for="project in selectedInvestigator.projects">
                            <div class="row-auto col-span-5" :id="'project-' + project.id">
                                <div class="grid grid-cols-5 dark:hover:bg-gray-700 hover:bg-gray-100 cursor-pointer">
                                    <p class="border dark:border-gray-600 p-2 border-r-0" @click="collapseAllAndToggle(project)">@{{ project.uid }}</p>
                                    <p class="border dark:border-gray-600 p-2 border-r-0" @click="project.hidden = 'hidden' in project ? ! project.hidden : false">@{{ project.name }}</p>
                                    <p class="border dark:border-gray-600 p-2" @click="project.hidden = 'hidden' in project ? ! project.hidden : false">@{{ moment(project.created_at).format('L') }}</p>
                                    <p class="border dark:border-gray-600 p-2" @click="project.hidden = 'hidden' in project ? ! project.hidden : false">@{{ moment(project.updated_at).format('L') }}</p>
                                    <a :href="'/projects/' + project.id + '/edit'" target="_blank" class="border dark:border-gray-600 p-2 text-center">View</a>
                                </div>
                            </div>

                            <div class="row-auto col-span-3 overflow-hidden pl-3 mb-5" :class="{'h-0 mb-0': ! ('hidden' in project) || ('hidden' in project && project.hidden)}" v-if="project.bio_samples.length > 0">
                                <p class="text-base mt-3">Bio Samples</p>

                                <div class="grid grid-cols-3 mt-3">
                                    <div class="row-auto col-span-3">
                                        <div class="grid grid-cols-6 border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-800">
                                            <p class="p-2 border-r border-gray-300 dark:border-gray-600">UID</p>
                                            <p class="p-2 border-r border-gray-300 dark:border-gray-600">Label</p>
                                            <p class="p-2 border-r border-gray-300 dark:border-gray-600">Location</p>
                                            <p class="p-2 border-r border-gray-300 dark:border-gray-600">Created At</p>
                                            <p class="p-2 border-r border-gray-300 dark:border-gray-600">Updated At</p>
                                            <p class="border-r border-gray-300 dark:border-gray-600 p-2"></p>
                                        </div>
                                    </div>

                                    <div v-if="project.bio_samples.length === 0" class="row-auto col-span-full border border-gray-300 dark:border-gray-600 p-2 border-t-0">
                                        <p>This project has no assigned bio samples.</p>
                                    </div>

                                    <template v-for="bioSample in project.bio_samples">
                                        <div class="row-auto col-span-3" :id="'bio-sample-' + bioSample.id" @click="collapseAllAndToggle(bioSample)">
                                            <div class="grid grid-cols-6 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                <p class="border dark:border-gray-600 p-2 border-r-0">@{{ bioSample.uid }}</p>
                                                <p class="border dark:border-gray-600 p-2 border-r-0">@{{ bioSample.label }}</p>
                                                <p class="border dark:border-gray-600 p-2 border-r-0">@{{ bioSample.location.location }}</p>
                                                <p class="border dark:border-gray-600 p-2 border-r-0">@{{ moment(bioSample.created_at).format('L') }}</p>
                                                <p class="border dark:border-gray-600 p-2">@{{ moment(bioSample.updated_at).format('L') }}</p>
                                                <a :href="'/bio-samples/' + bioSample.id + '/edit'" target="_blank" class="border dark:border-gray-600 p-2 text-center">View</a>
                                            </div>
                                        </div>

                                        <div class="row-auto col-span-3">
                                            <div class="grid grid-cols-3">
                                                <div class="row-auto col-span-3 overflow-hidden pl-3 mb-5" :class="{'h-0 mb-0': ! ('hidden' in bioSample) || ('hidden' in bioSample && bioSample.hidden)}">
                                                    <p class="text-base mt-3">Samples</p>

                                                    <div class="grid grid-cols-3 mt-3">
                                                        <div class="row-auto col-span-3">
                                                            <div class="grid grid-cols-6 border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-800">
                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">UID</p>
                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">Label</p>
                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">Location</p>
                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">Created At</p>
                                                                <p class="p-2">Updated At</p>
                                                                <p class="border-r border-gray-300 dark:border-gray-600 p-2"></p>
                                                            </div>
                                                        </div>

                                                        <div v-if="bioSample.samples.length === 0" class="row-auto col-span-full border border-gray-300 dark:border-gray-600 p-2 border-t-0">
                                                            <p>This bio sample has no assigned samples.</p>
                                                        </div>

                                                        <template v-for="sample in bioSample.samples">
                                                            <div class="row-auto col-span-3" @click="collapseAllAndToggle(sample)">
                                                                <div class="grid grid-cols-6 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ sample.uid }}</p>
                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ sample.label }}</p>
                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ sample.location.location }}</p>
                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ moment(sample.created_at).format('L') }}</p>
                                                                    <p class="border dark:border-gray-600 p-2">@{{ moment(sample.updated_at).format('L') }}</p>
                                                                    <a :href="'/samples/' + sample.id + '/edit'" target="_blank" class="border dark:border-gray-600 p-2 text-center">View</a>
                                                                </div>
                                                            </div>

                                                            <div class="row-auto col-span-3">
                                                                <div class="grid grid-cols-3">
                                                                    <div class="col-span-4">
                                                                        <div class="row-auto col-span-3">
                                                                            <div class="grid grid-cols-3">
                                                                                <div class="row-auto col-span-3 overflow-hidden pl-3 mb-5" :class="{'h-0 mb-0': ! ('hidden' in sample) || ('hidden' in sample && sample.hidden)}">
                                                                                    <p class="text-base mt-3">Specimens</p>

                                                                                    <div class="grid grid-cols-3 mt-3">
                                                                                        <div class="row-auto col-span-3">
                                                                                            <div class="grid grid-cols-6 border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-800">
                                                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">UID</p>
                                                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">Label</p>
                                                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">Location</p>
                                                                                                <p class="p-2 border-r border-gray-300 dark:border-gray-600">Created At</p>
                                                                                                <p class="border-r border-gray-300 dark:border-gray-600 p-2">Updated At</p>
                                                                                                <p class="border-r border-gray-300 dark:border-gray-600 p-2"></p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div v-if="sample.specimens.length === 0" class="row-auto col-span-full border border-gray-300 dark:border-gray-600 p-2 border-t-0">
                                                                                            <p>This sample has no assigned specimens.</p>
                                                                                        </div>

                                                                                        <template v-for="specimen in sample.specimens">
                                                                                            <div class="row-auto col-span-3">
                                                                                                <div class="grid grid-cols-6 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ specimen.uid }}</p>
                                                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ specimen.label }}</p>
                                                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ specimen.location.location }}</p>
                                                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ moment(specimen.created_at).format('L') }}</p>
                                                                                                    <p class="border dark:border-gray-600 p-2 border-r-0">@{{ moment(specimen.updated_at).format('L') }}</p>
                                                                                                    <a :href="'/specimens/' + specimen.id + '/edit'" target="_blank" class="border dark:border-gray-600 p-2 text-center">View</a>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="row-auto col-span-3">
                                                                                                <div class="grid grid-cols-3">
                                                                                                    <p class="col-span-4"><!-- Next table --></p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </template>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const { createApp, ref, onMounted, onUpdated, nextTick } = Vue

        createApp({
            setup() {
                let data = {!! $data->toJson() !!};
                let investigators = {!! $data->pluck('name', 'id')->toJson() !!};
                let selectedInvestigator = ref(false);

                function handleInvestigatorChange(e) {
                    selectedInvestigator.value = data.filter(i => i.id === parseInt(e.target.value))[0];
                }

                function collapseAllAndToggle(obj) {
                    selectedInvestigator.value.projects.forEach(project => {
                        project.bio_samples.forEach((bioSample) => {
                            bioSample.samples.forEach(sample => {
                                if (('specimens' in obj || 'samples' in obj || 'bio_samples' in obj) && sample.id !== obj.id) {
                                    sample.hidden = true;
                                }
                            })

                            if (('samples' in obj || 'bio_samples' in obj) && bioSample.id !== obj.id) {
                                bioSample.hidden = true;
                            }
                        })

                        if ('bio_samples' in obj && obj.id !== project.id) {
                            project.hidden = true;
                        }
                    })

                    obj.hidden = 'hidden' in obj ? ! obj.hidden : false
                }

                return {
                    data,
                    investigators,
                    selectedInvestigator,
                    handleInvestigatorChange,
                    moment,
                    collapseAllAndToggle
                }
            }
        }).mount('#vueApp')
    </script>
</x-filament-panels::page>

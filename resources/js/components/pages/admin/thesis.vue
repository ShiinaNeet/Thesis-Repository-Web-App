<template>
    <div class="mx-5 mb-2 py-5 px-2.5 pb-2.5 bg-white rounded overflow-y-auto">       
        <va-data-table
       
        :items="thesisList"
        :columns="keyconfig.tblColumns"
        :per-page="$root.config.tblPerPage"
        :current-page="$root.config.tblCurrPage"
        no-data-html="No Thesis(s) to show"
        :filter="filter"
        @filtered="filtered = $event.items"
        animated
        itemSize="250px"
        class="w-full"
        :loading="gettingThesis"
        >
            <template #headerAppend>
                <tr class="table-crud__slot " >
                    <th
                    v-for="key in Object.keys(createThesis.data)"
                    :key="key"
                    class="py-1 pr-1 "
                    >
                        <va-input
                        v-if="key.includes('title')"
                        v-model="filter"
                        placeholder="Search..."
                        class="w-full"
                        >
                            <template #appendInner>
                                <va-icon name="search" color="#C2C2C2" />
                            </template>
                        </va-input>
                    
                        <va-button
                        v-if="key.includes('id')"
                        preset="primary"
                        icon="post_add"
                        class="m-0 p-0 align-content w-full"
                        @click="createThesis.modal = !createThesis.modal"
                        >
                            Add
                        </va-button>
                    </th>
                </tr>
            </template>

            <template #cell(title)="{ value }">
                <div class="truncate text-wrap"
                >
                    {{ value }}
                </div>
            </template>
           
            <template #cell(published_at)="{ value }">
                {{ formatDate(value, 'MMM. Do YYYY', 'Invalid Date') }}
            </template>
            <template #cell(id)="{ rowData }">
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Edit"
                preset="plain"
                icon="edit"
                :disabled="rowData.deleted_at !== null"
                @click="editThesis.data.title = rowData.title,
                        editThesis.data.abstract = rowData.abstract,
                        editThesis.data.published_at = rowData.published_at,
                        editThesis.data.authors = rowData.authors,
                        editThesis.data.keywords = rowData.keywords,
                        editThesis.data.categories = rowData.categories,
                        editThesis.data.id = rowData.id,
                        editThesis.modal = !editThesis.modal"
                />
                <va-button
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Toggle Status"
                preset="plain"
                :icon="rowData.deleted_at ? 'lock' : 'lock_open'"
                
                @click="editThesis.data = { ...rowData }, editThesis.statusModal = !editThesis.statusModal"
                />
                <va-button
                v-if="$root.auth.userType !== 1 && $root.auth.userType !== 3"
                class="mb-2 mr-2 hover:opacity-[0.65!important]"
                title="Delete"
                preset="plain"
                icon="delete"
                :disabled="rowData.deleted_at !== null"
                @click="editThesis.data = { ...rowData }, editThesis.deleteModal = !editThesis.deleteModal"
                />
            </template>
            <template #cell(abstract)="{ row, isExpanded }">
                <va-button
                class="w-full"
                preset="secondary"
                size="small"
                :icon="isExpanded ? 'va-arrow-up': 'va-arrow-down'"
                @click="row.toggleRowDetails(), activePreviewRow = row"
                >
                    {{ !isExpanded ? 'Show': 'Hide' }}
                </va-button>
            </template>
            <template #expandableRow="{ rowData }">
                <div
                class="p-2 flex max-[700px]:flex-col max-[700px]:w-10/12 overflow-x-auto"
                id=" "
                >
                    <div class="w-full h-full text-wrap flex-wrap rounded-lg flex flex-center justify-center min-[700px]:w-1/2">
                        <div class="h-full max-screen:flex-center p-3">
                            <video class="h-1/2 w-full md:h-[300px] sm:h-[250px] lg:h-1/2 justify-center flex flex-center text-center "  v-if="rowData.video !== null" controls>
                                <source :src="rowData.video"  type="video/mp4">  
                            </video> 
                            <div
                            v-else
                            class="md:h-[300px] sm:h-[300px] lg:h-[250px] w-full justify-center flex flex-center text-center"
                            >
                                    No video
                               
                            </div>  
                        </div>
                           
                    </div>
                    <div class=" w-1/2 p-3 h-full justify-center rounded-lg max-[700px]:w-full  ">
                        <h1 class="text-2xl text-wrap break-all text-center font-mono uppercase overflow-x-auto">
                        {{ rowData.title }}   
                        <span class="uppercase text-lg font-sans">
                            <h3>{{ formatDate(rowData.published_at,'MMM. Do YYYY', 'Invalid Date') }}   </h3> 
                        </span>
                        </h1>
                        
                        <div class="w-full py-3 pt-4 ">
                            <div class=" lg:flex-row flex-col gap-1">
                                <!-- <h3 class="text-lg uppercase font-sans">Author:</h3>  -->
                                <span class="flex flex-wrap gap-1">
                                <!-- Loop through the first three authors -->
                                <template v-for="(author, index) in rowData.authors.slice(0, 2)" :key="author.id">
                                    <VaChip
                                        :text="author.name"
                                        color="info"
                                        text-color="BackgroundPrimary"
                                        size="small"
                                        square
                                        class="p-0.5"
                                    >
                                        <span  class="p-1">{{ author.name }}</span>
                                    </VaChip>
                                </template>

                                <!-- Check if there are remaining authors -->
                                <template v-if="rowData.authors.length > 2">
                                  
                                    <VaDropdown trigger="hover">
                                        <template #anchor>
                                            <VaButton class="mr-2 font-serif" size="small" color="info">
                                                <span class="text-large p-0.5 font-sans" >+{{ rowData.authors.length - 2 }} more</span>
                                            </VaButton>
                                        </template>

                                        <VaDropdownContent 
                                        v-for="(author, index) in rowData.authors.slice(2)"
                                        >
                                           <span class="flex flex-center justify-center">
                                            {{ author.name }}
                                           </span> 
                                        </VaDropdownContent>
                                    </VaDropdown>
                                </template>
                                </span>
                            <span 
                            v-if="rowData.authors == ''"
                            class="flex flex-wrap text-wrap items-center py-2 uppercase font-sans text-bold"
                            > No registered author</span>
                            </div>
                        </div>
                        
                        <VaDivider>
                           
                        </VaDivider>
                        <span class="px-2 font-bold text-lg p1-5 flex-center justify-center">Abstract</span>
                        <div class="bg-white-500 w-full py-1 pt-3 overflow-x-auto">
                            <p class="text-wrap text-justify py-2 whitespace-pre-line overflow-x-auto">
                                {{ rowData.abstract === 'null' || rowData.abstract === '' ||rowData.abstract === null ? 'Abstract Missing!' : rowData.abstract }}
                            </p>
                        </div>
                        <VaDivider> </VaDivider class="lg:py-5 sm:py-5">
                        <span class="px-2 font-bold text-lg py-1 text-center flex-center">Tags</span>
                        <div class=" w-fit py-2">
                            <div class="flex lg:flex-row flex-col gap-1 py-1">
                               
                                <span class="flex flex-wrap items-center gap-1">
                                <!-- Loop through the first three authors -->
                                <template v-for="(keyword, index) in rowData.keywords.slice(0, 3)" :key="keyword.id">
                                    <VaChip
                                        :text="keyword.keyword"
                                        color="info"
                                        text-color="BackgroundPrimary"
                                        size="small"
                                        square
                                        class="p-0.5"
                                    >
                                        <span  class="p-1">{{ keyword.keyword }}</span>
                                    </VaChip>
                                </template>

                                <!-- Check if there are remaining authors -->
                                <template v-if="rowData.keywords.length > 3">
                                  
                                    <VaDropdown trigger="hover">
                                        <template #anchor>
                                            <VaButton class="mr-2 font-serif" size="small" color="info">
                                                <span class="text-large p-0.5 font-sans" >+{{ rowData.keywords.length - 3 }} more</span>
                                            </VaButton>
                                        </template>

                                        <VaDropdownContent 
                                        v-for="(keyword, index) in rowData.keywords.slice(3)"
                                        >
                                           <span class="flex flex-center justify-center">
                                            {{ keyword.keyword }}
                                           </span> 
                                        </VaDropdownContent>
                                    </VaDropdown>
                                </template>
                                </span>
                                <span 
                                v-if="rowData.keywords == ''"
                                class="flex flex-wrap text-wrap items-center"
                                > No keyword Tagged</span>
                            </div>
                        </div>
                        <VaDivider>
                        </VaDivider class="lg:py-0 sm:py-0">
                        <span class="px-2 font-bold text-lg py-1 text-center flex-center">Category</span>
                        <div class=" w-fit py-2">
                            <div class="flex lg:flex-row flex-col gap-1 py-1">
                               
                                <span class="flex flex-wrap items-center gap-1">
                                <!-- Loop through the first three authors -->
                                <template v-for="(category, index) in rowData.categories.slice(0, 3)" :key="category.id">
                                    <VaChip
                                        :text="category.category"
                                        color="warning"
                                        text-color="BackgroundPrimary"
                                        size="small"
                                        square
                                        class="p-0.5"
                                    >
                                        <span  class="p-1">{{ category.category }}</span>
                                    </VaChip>
                                </template>

                                <!-- Check if there are remaining authors -->
                                <template v-if="rowData.categories.length > 2">
                                  
                                    <VaDropdown trigger="hover">
                                        <template #anchor>
                                            <VaButton class="mr-2 font-serif" size="small" color="warning">
                                                <span class="text-large p-0.5 font-sans" >+{{ rowData.categories.length - 3 }} more</span>
                                            </VaButton>
                                        </template>

                                        <VaDropdownContent 
                                        v-for="(category, index) in rowData.categories.slice(2)"
                                        >
                                           <span class="flex flex-center justify-center">
                                            {{ category.category }}
                                           </span> 
                                        </VaDropdownContent>
                                    </VaDropdown>
                                </template>
                                </span>
                                <span 
                                v-if="rowData.categoryList == ''"
                                class="flex flex-wrap text-wrap items-center"
                                > No Category Tagged</span>
                            </div>
                        </div>
                        <VaDivider
                        class="lg:py-5 sm:py-5"
                        >
                            <span class="px-2 font-bold text-lg">Files</span>
                        </VaDivider>
                        <div
                        class="py-2">
                            <h2 class="text-lg pdf py-2">
                                PDF :   <a 
                                    :class="['text-black', 'py-2', rowData.pdf !== null ? 'underline' : '', rowData.pdf !== null ? 'text-blue-500' : '']"
                                        :href="rowData.pdf">
                                            {{ rowData.pdf !== null ? 'Download' : 'Unavailable'}}
                                        </a>
                            </h2>
                        </div>
                    </div>
                </div>
            </template>
            <template #bodyAppend>
                <tr v-if="$root.tblPagination(thesisList)">
                    <td
                    id="pagination"
                    :colspan="keyconfig.tblColumns.length"
                    >
                        <div class="flex pt-[10px] select-none">
                            <va-pagination
                            class="justify-center"
                            v-model="$root.config.tblCurrPage"
                            :pages="filter == '' ? $root.tblPagination(thesisList) : (pages, $root.config.tblCurrPage = 1)"
                            input
                            />
                        </div>
                    </td>
                </tr>
            </template>
        </va-data-table>
    </div>

    <va-modal
    v-model="createThesis.modal"
    noOutsideDismiss
    noPadding
    >
        <template #content>
            <VaProgressBar  :indeterminate="createThesis.isloading" />
            <VaInnerLoading 
            icon="none"
            :loading="createThesis.isloading"
            :size="70"
            >
                <div v-if="createThesis.isloading" class="z-50 flex flex-center h-screen absolute top-0 left-0 w-full">
                    <div class="text-center text-3xl font-bold text-black p-2">Saving... <br/>{{ UploadProgressStatus }} %</div>
                </div>
                <div class="w-full px-5 flex flex-center">
                    <div class="py-10">
                        <div class="va-title mb-3">
                        Add Thesis
                        </div>
                        <form action="POST">
                            <va-input
                            v-model="createThesis.data.title"
                            label="Title *"
                            class="w-full mb-2"
                            maxlength="120"
                            :error="createThesis.TitleEmpty && (createThesis.data.title === '' || createThesis.data.title === null)"
                            :error-messages="'The Title field is required.'"
                            immediate-validation
                            />
                            <div class="select w-full">
                                <VaSelect
                                class="w-1/2"
                                v-model="createThesis.data.keywords"
                                label="Keywords to attach"
                                :options="keywordList"
                                text-by="keyword"
                                multiple
                                searchable
                                clearable
                                clearable-icon="backspace"
                                @update:modelValue="createThesis.keywordsEmpty = false"
                                >
                                    <template #content="{ value }">
                                        <va-chip
                                            v-for="req in value"
                                            :key="req"
                                            size="small"
                                            color="secondary"
                                            class="mr-1 mb-1"
                                            square
                                            closeable
                                            @update:modelValue="deleteChipCreateKeyword(req)"
                                        >
                                            {{ req.keyword }}
                                        </va-chip>
                                    </template>
                                </VaSelect>
                                <VaSelect
                                class="w-1/2"
                                v-model="createThesis.data.categories"
                                label="Category to attach"
                                :options="categoryList"
                                text-by="category"
                                searchable
                                clearable
                                multiple
                                clearable-icon="backspace"
                                @update:modelValue="createThesis.categoryEmpty = false"
                                >
                                    <template #content="{ value }">
                                        <va-chip
                                        v-for="reqw in value"
                                        :key="reqw"
                                        size="small"
                                        color="secondary"
                                        class="mr-1 mb-1"
                                        square
                                        closeable
                                        @update:modelValue="DeleteChipCreateCategory(reqw)"
                                        >
                                            {{ reqw.category }}
                                        </va-chip>
                                    </template>
                                </VaSelect>
                                <VaSelect
                                v-model="createThesis.data.authors"
                                :options="authorList"
                                class="w-full"
                                label="Author"
                                text-by="name"
                                multiple
                                searchable
                                clearable
                                clearable-icon="backspace"
                                @update:modelValue="createThesis.authorEmpty = false"
                                >
                                    <template #content="{ value }">
                                        <va-chip
                                        v-for="reqaa in value"
                                        :key="reqaa"
                                        size="small"
                                        color="secondary"
                                        class="mr-1 mb-1"
                                        square
                                        closeable
                                        @update:modelValue="deleteChipCreateAuthor(reqaa)"
                                        >
                                            {{ reqaa.name }}
                                        </va-chip>
                                    </template>
                                </VaSelect>
                            </div>
                            <VaTextarea
                            v-model="createThesis.data.abstract"
                            label="Abstract *"
                            class="w-full h-fit mb-2"
                            :autosize="true"
                            :maxLength="3000"
                            counter
                            max-rows="7"
                            min-rows="7"
                            :error="createThesis.AbstractEmpty && (createThesis.data.abstract === '' || createThesis.data.abstract === null)"
                            :error-messages="'The Abstract field is required.'"
                            immediate-validation
                            />
                            <va-input
                            v-model="createThesis.data.published_at"
                            label="Publish date *"
                            type="date"
                            class="w-full mb-2"
                            :format="formatDatePicker"
                            :rules="[(v) => v && v.length > 0 || 'The Published Date field is required.']"
                            :error="createThesis.PublishedDateEmpty && (createThesis.data.published_at === '' || createThesis.data.published_at === null)"
                            :error-messages="'The Published Date field is required.'"
                            @keyup="createThesis.PublishedDateEmpty = false"
                            />
                            <VaFileUpload
                            v-model="createThesis.data.video"
                            file-types="mp4"
                            dropZoneText="Drop your video here."
                            dropzone
                            id="editvideo"  
                            ref="videoInput"
                            name="file" 
                            />
                            <VaFileUpload
                            v-model="createThesis.data.pdf"
                            file-types="pdf"
                            dropZoneText="Drop your pdf here."
                            dropzone
                            id="editvideo"  
                            ref="pdfInput"
                            name="file" 
                            />
                                <div class="flex w-full gap-x-3 mt-[15px]">
                                    <div class="flex w-1/2 justify-between">
                                        <va-button
                                        preset="secondary"
                                        @click="
                                            createThesis.data = { ...createThesis.resetData },
                                            createThesis.TitleEmpty = false,
                                            createThesis.AbstractEmptyEmpty = false,
                                            createThesis.PublishedDateEmpty = false,
                                            createThesis.saved = false,
                                            createThesis.modal = !createThesis.modal
                                        "
                                        >
                                            <p class="font-normal">Cancel</p>
                                        </va-button>
                                    </div>
                                    <div class="flex w-1/2 justify-between">
                                        <va-button
                                        icon="save"
                                        :loading="createThesis.saved"
                                        :disabled="createThesis.saved"
                                        @click="createThesis.saved = true, insertUpdateThesis('create')"
                                        type="submit"
                                        >
                                            <p class="font-normal">Save</p>
                                        </va-button>
                                    </div>
                                </div>
                        </form>
                    </div>
                    
                </div>
            </VaInnerLoading>
        </template>
    </va-modal>

    <va-modal
    v-model="editThesis.modal"
    noOutsideDismiss
    noPadding
    >
        <template #content>
            <VaInnerLoading 
            :loading="editThesis.isloading"
            :size="50"
            icon="none"
            >
                
             
                <div class="w-full p-5 flex flex-center justify-center">
                    <div v-if="editThesis.isloading" class="z-50 flex flex-center h-screen absolute top-0 left-0 w-full">
                        <div class="text-center text-3xl font-bold text-black p-2">Saving... <br/>{{ UploadProgressStatus }} %</div>
                    </div>
                    <div class=" justify-center pt-10">
                        <div class="va-title mb-3">
                            Edit Thesis
                        </div>
                        <va-input
                        v-model="editThesis.data.title"
                        label="Title *"
                        class="w-full mb-2"
                        maxlength="120"
                        :error="editThesis.TitleEmpty && (editThesis.data.title == '' || editThesis.data.title == null)"
                        :error-messages="'The Title field is required.'"
                        @keyup="editThesis.TitleEmpty = false"
                        immediate-validation
                        />
                        <div class="select w-full">
                            <!-- Error in EditThesis.data.keywords IDK WHY -->
                            <va-select
                            v-model="editThesis.data.keywords"
                            :options="keywordList"
                            text-by="keyword"
                            class="w-1/2"
                            label="Keywords to attach"
                            multiple
                            searchable
                            clearable
                            clearable-icon="backspace"
                            :error="editThesis.keywordsEmpty"
                            :error-messages="'The requirement(s) field is required.'"
                            @update:modelValue="editThesis.key = 1"
                            >
                                <template #content="{ value }">
                                    <va-chip
                                        v-for="reqqsk in value"
                                        :key="reqqsk"
                                        size="small"
                                        color="secondary"
                                        class="mr-1 mb-1"
                                        square
                                        closeable
                                        @update:modelValue="deleteChipKeyword(reqqsk)"
                                       
                                    >
                                        {{ reqqsk.keyword }}
                                    </va-chip>
                                </template>
                            </va-select>
                            <VaSelect
                            v-model="editThesis.data.categories"
                            :options="categoryList"
                            text-by="category"
                            class="w-1/2"
                            label="Category to attach"
                            multiple
                            searchable
                            clearable-icon="backspace"
                            clearable
                            :error="editThesis.categoryEmpty"
                            :error-messages="'The category(s) field is required.'"
                            @update:modelValue="editThesis.cate = 1"
                            >
                                <template #content="{ value }">
                                    <VaChip
                                    v-for="ww in value"
                                    size="small"
                                    color="secondary"
                                    class="mr-1 mb-1"
                                    square
                                    closeable
                                    @update:modelValue="DeleteChipCategory(ww)"
                                    >
                                
                                    {{ ww.category }}
                                
                                    </VaChip>

                                </template>
                            </VaSelect>
                            <VaSelect
                            v-model="editThesis.data.authors"
                            :options="authorList"
                            text-by="name"
                            class="w-full"
                            label="Author"
                            multiple
                            searchable
                            clearable
                            clearable-icon="backspace"
                            @update:modelValue="editThesis.authr = 1"
                            >
                                <template #content="{ value }">
                                    <VaChip
                                    v-for="authorzx in value"
                                    :key="authorzx"
                                    size="small"
                                    color="secondary"
                                    class="mr-1 mb-1"
                                    square
                                    closeable
                                    @update:modelValue="deleteChipAuthor(authorzx) "
                                    >
                                    {{ authorzx.name }}
                                    
                                    </VaChip>
                                </template>
                            </VaSelect>
                        </div>
                        <VaTextarea
                        v-model="editThesis.data.abstract"
                        label="Abstract *"
                        class="w-full mb-2 whitespace-pre-line"
                        :autosize="true"
                        max-rows="7"
                        min-rows="7"
                        :error="editThesis.AbstractEmpty && (editThesis.data.abstract === '' || editThesis.data.abstract === null )"
                        :error-messages="'The Abstract field is required.'"
                        @keyup="editThesis.abstractEmpty = false"
                        immediate-validation
                        />
                        <VaDateInput
                        v-model="editThesis.data.published_at"
                        label="Published date *"
                        preset="bordered"
                        class="w-full mb-2"
                        :rules="[(v) => v && v.length > 0 || 'The Published Date field is required.']"
                        :error="editThesis.abstractEmpty"
                        :error-messages="'The Published Date field is required.'"
                        @keyup="editThesis.publishedDateEmpty = false"
                        />
                        <VaFileUpload
                        v-model="editThesis.data.video"
                        file-types="mp4"
                        dropZoneText="Drop your video here."
                        dropzone
                        id="editvideo"  
                        ref="videoInput"
                        name="file" 
                        />
                        <VaFileUpload
                        v-model="editThesis.data.pdf"
                        file-types="pdf"
                        dropZoneText="Drop your pdf here."
                        dropzone
                        id="editvideo"  
                        ref="pdfInput"
                        name="file" 
                        />
                        <div class="flex w-full gap-x-3 mt-[15px]">
                            <div class="flex w-1/2 justify-between">
                                <va-button
                                preset="secondary"
                                @click="
                                    editThesis.data = { ...createThesis.resetData },
                                
                                    editThesis.TitleEmpty = false,
                                    editThesis.abstractEmptyEmpty = false,
                                    editThesis.publishedDateEmpty = false,
                                    editThesis.saved = false,
                                    editThesis.modal = !editThesis.modal
                                ">
                                    <p class="font-normal">Cancel</p>
                                </va-button>
                            </div>
                            <div class="flex w-1/2 justify-between">
                                <va-button
                                icon="save"
                                :loading="editThesis.saved"
                                :disabled="editThesis.saved"
                                @click="editThesis.saved = true, insertUpdateThesis('save')"
                                >
                                    <p class="font-normal">Save</p>
                                </va-button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </VaInnerLoading>
        </template>
    </va-modal>

    <va-modal
    v-model="editThesis.deleteModal"
    @cancel="editThesis.data = { ...createThesis.resetData }"
    noPadding
    size="auto"
    >
        <template #content>
            <div class="w-full p-5">
                <div class="va-title mb-3">
                    Delete Thesis
                </div>
                <va-alert color="warning">
                    <template #icon>
                        <va-icon name="warning" />
                    </template>
                    This action is irreversible
                </va-alert>
                <VaTextarea
                :model-value="editThesis.data.title"
                class="w-full mb-2 "
                minRows="3"
                maxRows="5"
                readonly
                autosize
                />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="editThesis.data = { ...createThesis.resetData }, editThesis.deleteModal = !editThesis.deleteModal"
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        icon="check"
                        :loading="editThesis.saved"
                        :disabled="editThesis.saved"
                        @click="editThesis.saved = true, deleteThesis()"
                        >
                            <p class="font-normal">Confirm</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>

    <va-modal
    v-model="editThesis.statusModal"
    @cancel="editThesis.data = { ...createThesis.resetData }"
    noPadding
    size="auto"
    >
        <template #content>
            <div class="w-full p-5">
                <div class="va-title mb-3">
                    Thesis Status
                </div>
                
                <VaTextarea
                :model-value="editThesis.data.title"
                class="w-full mb-2 "
                minRows="3"
                maxRows="5"
                readonly
                autosize
                />
                <div class="flex w-full gap-x-3 mt-[15px]">
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        preset="secondary"
                        @click="editThesis.data = { ...createThesis.resetData }, editThesis.statusModal = !editThesis.statusModal"
                        >
                            <p class="font-normal">Cancel</p>
                        </va-button>
                    </div>
                    <div class="flex w-1/2 justify-between">
                        <va-button
                        :icon="!editThesis.data.deleted_at ? 'lock' : 'lock_open'"
                        :loading="editThesis.saved"
                        :disabled="editThesis.saved"
                        @click="editThesis.saved = true,handleButtonClick()"
                        >
                            <p class="font-normal">{{ !editThesis.data.deleted_at ? "Deactivate" : "Activate" }}</p>
                        </va-button>
                    </div>
                </div>
            </div>
        </template>
    </va-modal>
</template>

<style lang="scss" scoped>
    .table-crud {
        //--va-form-element-default-width: 80%;

        .va-input {
            display: block;
        }

        &__slot {
            th {
                vertical-align: middle;
                width: max-content;
            }
        }
    }
</style>

<script>




import formatDate from '@/functions/formatdate.js';
import { VaChip } from 'vuestic-ui/web-components';
const now = () => new Date();
const newThesis = {
    title:'',
    abstract:'',
    published_at: formatDate(now(),'YYYY-MM-DD', 'Invalid Date'),
    id: null,
    categories:[], 
    keywords:[],
    authors:[],
   
 
};

export default {
    data() {
        const keyconfig = {
            tblColumns: [
                { key: "title", label: "Title", width: "50%", sortable: true },
                { key: "abstract", label: "Abstract", width: "25%", sortable: false },
                { key: "published_at", label: "Published Date", width: "20%", sortable: true },
                { key: "id", label: "Action", width: "5%", sortable: false }
            ]
        };
        return {
            visibleAuthors: [],
            showRemainingAuthors: false,
            remainingAuthorsCount: 0,
            test: { keywords: {}, },
            files: [],
            gettingThesis: false,
            uploadFiles: [],
            showProgress: false,
            keyconfig,
            thesisList: [],
            keywordList: [],
            categoryList: [],
            authorList: [],
            createThesis: {
                isloading: false,
                modal: false,
                TitleEmpty: false,
                AbstractEmpty: false,
                PublishedDateEmpty: false,
                keywordsEmpty: false,
                categoryEmpty: false,
                authorEmpty: false,
                saved: false,
                resetData: { ...newThesis },
                data: { ...newThesis }
            },
            editThesis: {
                isloading: false,
                modal: false,
                deleteModal: false,
                statusModal: false,
                TitleEmpty: false,
                AbstractEmpty: false,
                keywordsEmpty: false,
                categoryEmpty: false,
                authorEmpty: false,
                PublishedDateEmpty: false,
                keywordsEmpty: false,
                saved: false,
                data: {},
                cate: 0,
                key: 0,
                authr:0,
            },
            UploadProgressStatus: 0,
            activePreviewRow: null,
            filtered: null,
            filter: ""
        };
    },
    computed: {
        pages() {
            return this.$root.config.tblPerPage && this.$root.config.tblPerPage !== 0
                ? Math.ceil(this.filtered.length / this.$root.config.tblPerPage)
                : this.filtered.length;
        },
    },
    mounted() {
        this.getThesis();
        this.getKeywords();
        this.getCategory();
        this.getAuthor();
    },
    methods: {
        deleteChipAuthor(author) {
           
            this.editThesis.data.authors = this.editThesis.data.authors.filter((authors) => authors !== author);
        },
        deleteChipKeyword(keyword) {
       
            const updatedKeywords = this.editThesis.data.keywords.filter(item => item.id !== keyword.id);
            this.editThesis.data.keywords = updatedKeywords;
          
        },
        DeleteChipCategory(category) {
            
            const updatedCategories = this.editThesis.data.categories.filter(item => item.id !== category.id);
            this.editThesis.data.categories = updatedCategories;
           
        },
        //Create Chip Celete below
        deleteChipCreateAuthor(author) {
           
           this.createThesis.data.authors = this.createThesis.data.authors.filter((authors) => authors !== author);
        },
        deleteChipCreateKeyword(keyword) {
        
            const updatedKeywords = this.createThesis.data.keywords.filter(item => item.id !== keyword.id);
            this.createThesis.data.keywords = updatedKeywords;
            
        },
        DeleteChipCreateCategory(category) {
            
            const updatedCategories = this.createThesis.data.categories.filter(item => item.id !== category.id);
            this.createThesis.data.categories = updatedCategories;
            
        },
        updateKeywordIds() {
            if (Array.isArray(this.editThesis.data.keywords)) {
            this.editThesis.data.keywords = this.editThesis.data.keywords.map(keywords => keywords.id);
            }
            
        },
        updateCategoryIds() {
            if (Array.isArray(this.editThesis.data.categories)) {
            this.editThesis.data.categories = this.editThesis.data.categories.map(categories => categories.id);
            }
           
        },
        updateAuthorIds() {
            if (Array.isArray(this.editThesis.data.authors)) {
            this.editThesis.data.authors = this.editThesis.data.authors.map(authors => authors.id);
            }
            
        },
        updateRequirementsArr(method, selectedKeywords) {
            if (method !== 'create' || method !== 'save') {
                if(method === 'create') { (this.createThesis.data.keywords =
                    this.createThesis.data.keywords.filter((v) => v !== this.createThesis.data.keywords[selectedKeywords]))
                 }
                else{  
                    this.editThesis.data.keywords = selectedKeywords.map(keyword => keyword.id);
                    this.editThesis.key = 1;
                }
            }
            else
                
                this.$root.prompt();
        },
        updateAuthorsArr(method, selectedAuthors) {
            if (method !== 'create' || method !== 'save') {
                if(method === 'create') {
                    this.createThesis.data.authors =
                        this.createThesis.data.authors.filter((v) => v !== this.createThesis.data.authors[selectedAuthors]);
                    
                 }
                 else{
                    
                    this.editThesis.data.authors = selectedAuthors.map(author => author.id);
                         this.editThesis.authr = 1;
                }
               
            }
            else
                this.$root.prompt();
        },
        updateCategoryArr(method, selectedCategories) {
            if (method !== 'create' || method !== 'save') {
                if(method === 'create') (this.createThesis.data.categories = this.createThesis.data.categories.filter((v) => v !== this.createThesis.data.category[idx]))
                else { 
                    if (method === 'save'){
                        this.editThesis.data.categories =
                        this.editThesis.data.categories.filter((v) => v !== this.editThesis.data.categories[selectedCategories]);
                        this.editThesis.cate = 1;
                    }
                }
               
            }
            else
                this.$root.prompt();
        },
        uploadFile(event) {
            const file = event.target.files[0];
        },
        handleButtonClick() {
            this.editThesis.saved = true;
            if (this.editThesis.data.deleted_at === null) {
                this.toggleHelpStatus();
            }
            else {
                this.enableHelpStatus();
            }
        },
        toggleHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/thesis/disable',
                data: { id: this.editThesis.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editThesis.data = { ...newThesis };
                    this.editThesis.statusModal = false;
                    this.editThesis.saved = false;
                    this.getThesis();
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        enableHelpStatus() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/thesis/enable',
                data: { id: this.editThesis.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editThesis.data = { ...newThesis };
                    this.editThesis.statusModal = false;
                    this.editThesis.saved = false;
                    this.getThesis();
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        deleteThesis() {
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/thesis/delete',
                data: { id: this.editThesis.data.id }
            }).then(response => {
                if (response.data.status == 1) {
                    this.$root.prompt(response.data.text);
                    this.editThesis.data = { ...newThesis };
                    this.editThesis.deleteModal = false;
                    this.editThesis.saved = false;
                    this.getThesis();
                    this.activePreviewRow && this.activePreviewRow.toggleRowDetails(false);
                    this.activePreviewRow = null;
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        insertUpdateThesis(method) {
            if (method !== 'create' || method !== 'save') {
                let formData = new FormData();
                // Append other form data
                if (method == 'create') {
                    formData.append('title', this.createThesis.data.title);
                    formData.append('abstract', this.createThesis.data.abstract);
                    var newDate = this.formatDate(this.createThesis.data.published_at, 'YYYY-MM-DD');
                    formData.append('published_at', newDate);
                    if (this.createThesis.data.video && this.createThesis.data.video.length > 0) {
                        const file = this.createThesis.data.video[0];
                        formData.append('video', file);
                    }
                    if (this.createThesis.data.pdf && this.createThesis.data.pdf.length > 0) {
                        const pdffile = this.createThesis.data.pdf[0];
                        formData.append('pdf', pdffile);
                    }
                   
                    formData.append('authors', JSON.stringify(this.createThesis.data.authors));
                    formData.append('categories', JSON.stringify(this.createThesis.data.categories));
                    formData.append('keywords', JSON.stringify(this.createThesis.data.keywords));
               
                }
                else {
                    formData.append('id', this.editThesis.data.id);
                    formData.append('title', this.editThesis.data.title);
                    formData.append('abstract', this.editThesis.data.abstract);
                    if (this.editThesis.data.video && this.editThesis.data.video.length > 0) {
                        const file = this.editThesis.data.video[0];
                        
                        formData.append('video', file);
                    }
                    if (this.editThesis.data.pdf && this.editThesis.data.pdf.length > 0) {
                        const pdffile = this.editThesis.data.pdf[0];
                        formData.append('pdf', pdffile);
                    }
                  
                   
                    var newDate = this.formatDate(this.editThesis.data.published_at, 'YYYY-MM-DD');
                    formData.append('published_at', newDate); 
                   
                    // if(this.editThesis.key == 0)
                    // {
                    //     this.updateKeywordIds();
                    // }
                    // if(this.editThesis.authr == 0)
                    // {
                    //     this.updateAuthorIds();
                    // }
                    // if(this.editThesis.cate == 0)
                    // {
                    //     this.updateCategoryIds();
                    // }
                   
                    
                    formData.append('authors', JSON.stringify(this.editThesis.data.authors));
                    formData.append('categories', JSON.stringify(this.editThesis.data.categories));
                    formData.append('keywords', JSON.stringify(this.editThesis.data.keywords));
                
                }
                const config = {
                    onUploadProgress: (progressEvent) => {
                        const percentCompleted = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                        console.log(percentCompleted)
                        this.UploadProgressStatus = percentCompleted
                        if (percentCompleted === 100) {
                            this.createThesis.isloading = false;
                            this.editThesis.isloading = false;
                            
                        }
                    }
                }
                this.createThesis.isloading = true;
                this.editThesis.isloading = true;
              
                axios.post('/thesis/save', formData, config).then(response => {
                    if (response.data.status == 1) {
                        this.$root.prompt(response.data.text);
                        method === 'create' ? (this.createThesis.data = { ...newThesis },
                            this.createThesis.modal = false,
                            this.createThesis.TitleEmpty = false,
                            this.createThesis.AbstractEmpty = false,
                            this.createThesis.PublishedDateEmpty = false,
                            this.createThesis.saved = false)
                            : (method === 'save' && (this.editThesis.data = {},
                                this.editThesis.modal = false,
                                this.createThesis.TitleEmpty = false,
                                this.createThesis.AbstractEmpty = false,
                                this.createThesis.PublishedDateEmpty = false,
                                this.editThesis.data = {},
                                this.editThesis.saved = false));
                                this.getThesis();
                    }
                    else {
                        this.editThesis.saved = false;
                        this.$root.prompt(response.data.message);
                        method === 'create' ? (this.createThesis.data = { ...newThesis },
                            this.createThesis.modal = false,
                            this.createThesis.TitleEmpty = false,
                            this.createThesis.AbstractEmpty = false,
                            this.createThesis.PublishedDateEmpty = false,
                            this.createThesis.saved = false)
                            : (method === 'save' && (this.editThesis.data = {},
                                this.editThesis.modal = false,
                                this.createThesis.TitleEmpty = false,
                                this.createThesis.AbstractEmpty = false,
                                this.createThesis.PublishedDateEmpty = false,
                                this.editThesis.saved = false));
                                this.getThesis();
                    }
                    this.getThesis();
                    this.createThesis.isloading = false;
                }).catch(error => {
                    this.getThesis();

                    let resDataError = Object.keys(error.response.data.errors);
                    if (resDataError.filter(key => key == 'message').length > 0) {
                        method === 'create' ? (this.createThesis.data = { ...newThesis },
                            console.log(error.response.data),
                            this.$root.prompt(error.response.data.message),
                            this.createThesis.modal = false,
                            this.createThesis.TitleEmpty = false,
                            this.createThesis.AbstractEmpty = false,
                            this.createThesis.PublishedDateEmpty = false,
                            this.createThesis.saved = false)
                            : (method === 'save' && (this.editThesis.data = {},
                                this.editThesis.modal = false,
                                this.createThesis.TitleEmpty = false,
                                this.createThesis.AbstractEmpty = false,
                                this.createThesis.PublishedDateEmpty = false,
                                this.editThesis.saved = false));
                                this.getThesis();
                    }
                    if (resDataError.includes('title')) {
                        this.createThesis.TitleEmpty = true;
                        this.editThesis.TitleEmpty = true;
                    }
                    if (resDataError.includes('abstract')) {
                        this.createThesis.AbstractEmpty = true;
                        this.editThesis.AbstractEmpty = true;
                    }
                    if (resDataError.includes('published_at')) {
                        method === 'create' ? this.createThesis.PublishedDateEmpty = true
                            : (method === 'save' && (this.editThesis.PublishedDateEmpty = true));
                    }
                    if(method === 'create' ) this.createThesis.saved = false;
                    else {
                        this.editThesis.saved = false;
                    }
                    this.getThesis(); 
                    this.$root.prompt(error.response.data.message)   
                });
            }
            else
                this.$root.prompt();
        },
        getThesis() {
            this.gettingThesis = true;
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/thesis/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.thesisList = response.data.result;
                    
                }
                else{
                    this.$root.prompt(response.data.text);
                }
                this.gettingThesis = false;
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
                this.gettingThesis = false;
            });
        },
        getKeywords() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/keyword/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.keywordList = response.data.result;
                  
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        getCategory() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/category/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.categoryList = response.data.result;
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        getAuthor() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: '/author/get'
            }).then(response => {
                if (response.data.status == 1) {
                    this.authorList = response.data.result;
                }
                else
                    this.$root.prompt(response.data.text);
            }).catch(error => {
                this.$root.prompt(error.response.data.message);
            });
        },
        formatDatePicker(date) {
            return this.formatDate(date, 'YYYY-MM-DD', 'Invalid Date');
           
        },
        formatDate,
    },
    components: { VaChip }
}
</script>

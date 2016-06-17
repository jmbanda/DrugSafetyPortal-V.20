<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */


    include_once dirname(__FILE__) . '/' . 'components/utils/check_utils.php';
    CheckPHPVersion();
    CheckTemplatesCacheFolderIsExistsAndWritable();


    include_once dirname(__FILE__) . '/' . 'phpgen_settings.php';
    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
        return $result;
    }

    
    // OnGlobalBeforePageExecute event handler
    
    
    // OnBeforePageExecute event handler
    
    
    
    class datasetPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`dataset`');
            $field = new IntegerField('dataset_id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('dataset_name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('authors');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('unique_drugs');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('unique_events');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('drug_vocabulary');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('event_vocabulary');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('published');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('journal_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('paper_title');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('paper_authros');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('pubmed_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('doi');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('curator_name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('curator_email');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('upload_date');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('dataset_file');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('drug_vocabulary', 'drug_vocabularies', new IntegerField('drug_vocab_id', null, null, true), new StringField('vocab_name', 'drug_vocabulary_vocab_name', 'drug_vocabulary_vocab_name_drug_vocabularies'), 'drug_vocabulary_vocab_name_drug_vocabularies');
            $this->dataset->AddLookupField('event_vocabulary', 'event_vocabularies', new IntegerField('event_vocab_id', null, null, true), new StringField('event_vocab_name', 'event_vocabulary_event_vocab_name', 'event_vocabulary_event_vocab_name_event_vocabularies'), 'event_vocabulary_event_vocab_name_event_vocabularies');
            $this->dataset->AddLookupField('journal_id', 'journals', new IntegerField('journal_id', null, null, true), new StringField('journal_title', 'journal_id_journal_title', 'journal_id_journal_title_journals'), 'journal_id_journal_title_journals');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
            $sql = 'SELECT label, total FROM (
            SELECT \'Published\' as label, count(dataset_id) as total FROM dataset WHERE published=1
            UNION
            SELECT \'Unpublished\' as label, count(dataset_id) as total FROM dataset WHERE published=0
            ) as xC;';
            
            $chart = new Chart('Chart01', Chart::TYPE_PIE, $this->dataset, $sql);
            $chart->setTitle($this->RenderText('Datasets in Drug Safety Portal'));
            $chart->setDomainColumn('label', $this->RenderText('label'), 'string');
            $chart->addDataColumn('total', $this->RenderText('Articles'), 'int');
            $this->addChart($chart, 0, ChartPosition::BEFORE_GRID, 5);
            
            $sql = 'SELECT label, total FROM (
            SELECT \'Drugs\' as label, sum(unique_drugs) as total FROM dataset
            UNION
            SELECT \'Events\' as label, sum(unique_events) as total FROM dataset
            ) as xC;';
            
            $chart = new Chart('Chart02', Chart::TYPE_BAR, $this->dataset, $sql);
            $chart->setTitle($this->RenderText('Unique Drugs and Events found in Drug Safety Portal'));
            $chart->setDomainColumn('label', $this->RenderText('label'), 'string');
            $chart->addDataColumn('total', $this->RenderText('Count'), 'int');
            $this->addChart($chart, 1, ChartPosition::BEFORE_GRID, 5);
        }
    
        protected function CreateGridSearchControl(Grid $grid)
        {
            $grid->UseFilter = true;
            $grid->SearchControl = new SimpleSearch('datasetssearch', $this->dataset,
                array('dataset_id', 'dataset_name', 'authors', 'unique_drugs', 'unique_events', 'drug_vocabulary_vocab_name', 'event_vocabulary_event_vocab_name', 'published', 'curator_name', 'curator_email'),
                array($this->RenderText('Dataset Id'), $this->RenderText('Dataset Name'), $this->RenderText('Authors'), $this->RenderText('Unique Drugs'), $this->RenderText('Unique Events'), $this->RenderText('Drug Vocabulary'), $this->RenderText('Event Vocabulary'), $this->RenderText('Published'), $this->RenderText('Curator Name'), $this->RenderText('Curator Email')),
                array(
                    '=' => $this->GetLocalizerCaptions()->GetMessageString('equals'),
                    '<>' => $this->GetLocalizerCaptions()->GetMessageString('doesNotEquals'),
                    '<' => $this->GetLocalizerCaptions()->GetMessageString('isLessThan'),
                    '<=' => $this->GetLocalizerCaptions()->GetMessageString('isLessThanOrEqualsTo'),
                    '>' => $this->GetLocalizerCaptions()->GetMessageString('isGreaterThan'),
                    '>=' => $this->GetLocalizerCaptions()->GetMessageString('isGreaterThanOrEqualsTo'),
                    'ILIKE' => $this->GetLocalizerCaptions()->GetMessageString('Like'),
                    'STARTS' => $this->GetLocalizerCaptions()->GetMessageString('StartsWith'),
                    'ENDS' => $this->GetLocalizerCaptions()->GetMessageString('EndsWith'),
                    'CONTAINS' => $this->GetLocalizerCaptions()->GetMessageString('Contains')
                    ), $this->GetLocalizerCaptions(), $this, 'CONTAINS'
                );
        }
    
        protected function CreateGridAdvancedSearchControl(Grid $grid)
        {
            $this->AdvancedSearchControl = new AdvancedSearchControl('datasetasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('dataset_id', $this->RenderText('Dataset Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('dataset_name', $this->RenderText('Dataset Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('authors', $this->RenderText('Authors')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('unique_drugs', $this->RenderText('Unique Drugs')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('unique_events', $this->RenderText('Unique Events')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`drug_vocabularies`');
            $field = new IntegerField('drug_vocab_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('vocab_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('vocab_uri_prefix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('vocab_name', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('drug_vocabulary', $this->RenderText('Drug Vocabulary'), $lookupDataset, 'drug_vocab_id', 'vocab_name', false, 8));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`event_vocabularies`');
            $field = new IntegerField('event_vocab_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('event_vocab_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('event_vocab_prefix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('event_vocab_name', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('event_vocabulary', $this->RenderText('Event Vocabulary'), $lookupDataset, 'event_vocab_id', 'event_vocab_name', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('published', $this->RenderText('Published')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('curator_name', $this->RenderText('Curator Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('curator_email', $this->RenderText('Curator Email')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('upload_date', $this->RenderText('Upload Date'), 'Y-m-d H:i:s'));
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for dataset_id field
            //
            $column = new TextViewColumn('dataset_id', 'Dataset Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for dataset_name field
            //
            $column = new TextViewColumn('dataset_name', 'Dataset Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_dataset_name_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for authors field
            //
            $column = new TextViewColumn('authors', 'Authors', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_authors_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for unique_drugs field
            //
            $column = new TextViewColumn('unique_drugs', 'Unique Drugs', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for unique_events field
            //
            $column = new TextViewColumn('unique_events', 'Unique Events', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vocab_name field
            //
            $column = new TextViewColumn('drug_vocabulary_vocab_name', 'Drug Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for event_vocab_name field
            //
            $column = new TextViewColumn('event_vocabulary_event_vocab_name', 'Event Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for published field
            //
            $column = new CheckboxViewColumn('published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $column->setDisplayValues($this->RenderText('<span class="pg-row-checkbox checked"></span>'), $this->RenderText('<span class="pg-row-checkbox"></span>'));
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for curator_name field
            //
            $column = new TextViewColumn('curator_name', 'Curator Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_curator_name_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for curator_email field
            //
            $column = new TextViewColumn('curator_email', 'Curator Email', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_curator_email_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for upload_date field
            //
            $column = new DateTimeViewColumn('upload_date', 'Upload Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for dataset_id field
            //
            $column = new TextViewColumn('dataset_id', 'Dataset Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dataset_name field
            //
            $column = new TextViewColumn('dataset_name', 'Dataset Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_dataset_name_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for authors field
            //
            $column = new TextViewColumn('authors', 'Authors', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_authors_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for unique_drugs field
            //
            $column = new TextViewColumn('unique_drugs', 'Unique Drugs', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for unique_events field
            //
            $column = new TextViewColumn('unique_events', 'Unique Events', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for vocab_name field
            //
            $column = new TextViewColumn('drug_vocabulary_vocab_name', 'Drug Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for event_vocab_name field
            //
            $column = new TextViewColumn('event_vocabulary_event_vocab_name', 'Event Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for published field
            //
            $column = new CheckboxViewColumn('published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $column->setDisplayValues($this->RenderText('<span class="pg-row-checkbox checked"></span>'), $this->RenderText('<span class="pg-row-checkbox"></span>'));
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for journal_title field
            //
            $column = new TextViewColumn('journal_id_journal_title', 'Journal Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for paper_title field
            //
            $column = new TextViewColumn('paper_title', 'Paper Title', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_paper_title_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for paper_authros field
            //
            $column = new TextViewColumn('paper_authros', 'Paper Authros', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_paper_authros_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for pubmed_id field
            //
            $column = new TextViewColumn('pubmed_id', 'Pubmed Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for doi field
            //
            $column = new TextViewColumn('doi', 'Doi', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for curator_name field
            //
            $column = new TextViewColumn('curator_name', 'Curator Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_curator_name_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for curator_email field
            //
            $column = new TextViewColumn('curator_email', 'Curator Email', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('datasetGrid_curator_email_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for upload_date field
            //
            $column = new DateTimeViewColumn('upload_date', 'Upload Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dataset_file field
            //
            $column = new DownloadExternalDataColumn('dataset_file', 'Dataset File', $this->dataset, '<img alt="download" src="images/download.gif"><br>' . $this->GetLocalizerCaptions()->GetMessageString('Download'), $this->GetLocalizerCaptions(), '');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for dataset_name field
            //
            $editor = new TextEdit('dataset_name_edit');
            $editColumn = new CustomEditColumn('Dataset Name', 'dataset_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(250, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for authors field
            //
            $editor = new TextEdit('authors_edit');
            $editColumn = new CustomEditColumn('Authors', 'authors', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(500, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for unique_drugs field
            //
            $editor = new TextEdit('unique_drugs_edit');
            $editColumn = new CustomEditColumn('Unique Drugs', 'unique_drugs', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for unique_events field
            //
            $editor = new TextEdit('unique_events_edit');
            $editColumn = new CustomEditColumn('Unique Events', 'unique_events', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for drug_vocabulary field
            //
            $editor = new ComboBox('drug_vocabulary_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`drug_vocabularies`');
            $field = new IntegerField('drug_vocab_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('vocab_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('vocab_uri_prefix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $editColumn = new LookUpEditColumn(
                'Drug Vocabulary', 
                'drug_vocabulary', 
                $editor, 
                $this->dataset, 'drug_vocab_id', 'vocab_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for event_vocabulary field
            //
            $editor = new ComboBox('event_vocabulary_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`event_vocabularies`');
            $field = new IntegerField('event_vocab_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('event_vocab_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('event_vocab_prefix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('event_vocab_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Event Vocabulary', 
                'event_vocabulary', 
                $editor, 
                $this->dataset, 'event_vocab_id', 'event_vocab_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for published field
            //
            $editor = new CheckBox('published_edit');
            $editColumn = new CustomEditColumn('Published', 'published', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for journal_id field
            //
            $editor = new ComboBox('journal_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`journals`');
            $field = new IntegerField('journal_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('journal_title');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('journal_website');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $editColumn = new LookUpEditColumn(
                'Journal Id', 
                'journal_id', 
                $editor, 
                $this->dataset, 'journal_id', 'journal_title', $lookupDataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for paper_title field
            //
            $editor = new TextEdit('paper_title_edit');
            $editColumn = new CustomEditColumn('Paper Title', 'paper_title', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(250, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for paper_authros field
            //
            $editor = new TextEdit('paper_authros_edit');
            $editColumn = new CustomEditColumn('Paper Authros', 'paper_authros', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(250, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for pubmed_id field
            //
            $editor = new TextEdit('pubmed_id_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Pubmed Id', 'pubmed_id', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(50, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for doi field
            //
            $editor = new TextEdit('doi_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Doi', 'doi', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(50, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for curator_name field
            //
            $editor = new TextEdit('curator_name_edit');
            $editColumn = new CustomEditColumn('Curator Name', 'curator_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(150, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for curator_email field
            //
            $editor = new TextEdit('curator_email_edit');
            $editColumn = new CustomEditColumn('Curator Email', 'curator_email', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(150, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for dataset_file field
            //
            $editor = new ImageUploader('dataset_file_edit');
            $editor->SetShowImage(false);
            $editColumn = new UploadFileToFolderColumn('Dataset File', 'dataset_file', $editor, $this->dataset, false, false, '');
            $editColumn->OnCustomFileName->AddListener('dataset_file_GenerateFileName_edit', $this);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for dataset_name field
            //
            $editor = new TextEdit('dataset_name_edit');
            $editColumn = new CustomEditColumn('Dataset Name', 'dataset_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(250, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for authors field
            //
            $editor = new TextEdit('authors_edit');
            $editColumn = new CustomEditColumn('Authors', 'authors', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(500, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for unique_drugs field
            //
            $editor = new TextEdit('unique_drugs_edit');
            $editColumn = new CustomEditColumn('Unique Drugs', 'unique_drugs', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for unique_events field
            //
            $editor = new TextEdit('unique_events_edit');
            $editColumn = new CustomEditColumn('Unique Events', 'unique_events', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for drug_vocabulary field
            //
            $editor = new ComboBox('drug_vocabulary_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`drug_vocabularies`');
            $field = new IntegerField('drug_vocab_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('vocab_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('vocab_uri_prefix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $editColumn = new LookUpEditColumn(
                'Drug Vocabulary', 
                'drug_vocabulary', 
                $editor, 
                $this->dataset, 'drug_vocab_id', 'vocab_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for event_vocabulary field
            //
            $editor = new ComboBox('event_vocabulary_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`event_vocabularies`');
            $field = new IntegerField('event_vocab_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('event_vocab_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('event_vocab_prefix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('event_vocab_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Event Vocabulary', 
                'event_vocabulary', 
                $editor, 
                $this->dataset, 'event_vocab_id', 'event_vocab_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for published field
            //
            $editor = new CheckBox('published_edit');
            $editColumn = new CustomEditColumn('Published', 'published', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for journal_id field
            //
            $editor = new ComboBox('journal_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`journals`');
            $field = new IntegerField('journal_id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('journal_title');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('journal_website');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $editColumn = new LookUpEditColumn(
                'Journal Id', 
                'journal_id', 
                $editor, 
                $this->dataset, 'journal_id', 'journal_title', $lookupDataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for paper_title field
            //
            $editor = new TextEdit('paper_title_edit');
            $editColumn = new CustomEditColumn('Paper Title', 'paper_title', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(250, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for paper_authros field
            //
            $editor = new TextEdit('paper_authros_edit');
            $editColumn = new CustomEditColumn('Paper Authros', 'paper_authros', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(250, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for pubmed_id field
            //
            $editor = new TextEdit('pubmed_id_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Pubmed Id', 'pubmed_id', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(50, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for doi field
            //
            $editor = new TextEdit('doi_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Doi', 'doi', $editor, $this->dataset);
            $editColumn->setVisible(false);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxLengthValidator(50, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for curator_name field
            //
            $editor = new TextEdit('curator_name_edit');
            $editColumn = new CustomEditColumn('Curator Name', 'curator_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(150, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for curator_email field
            //
            $editor = new TextEdit('curator_email_edit');
            $editColumn = new CustomEditColumn('Curator Email', 'curator_email', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(150, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for dataset_file field
            //
            $editor = new ImageUploader('dataset_file_edit');
            $editor->SetShowImage(false);
            $editColumn = new UploadFileToFolderColumn('Dataset File', 'dataset_file', $editor, $this->dataset, false, false, '');
            $editColumn->OnCustomFileName->AddListener('dataset_file_GenerateFileName_insert', $this);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $grid->SetShowAddButton(true);
                $grid->SetShowInlineAddButton(false);
            }
            else
            {
                $grid->SetShowInlineAddButton(false);
                $grid->SetShowAddButton(false);
            }
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for dataset_id field
            //
            $column = new TextViewColumn('dataset_id', 'Dataset Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for dataset_name field
            //
            $column = new TextViewColumn('dataset_name', 'Dataset Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for authors field
            //
            $column = new TextViewColumn('authors', 'Authors', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for unique_drugs field
            //
            $column = new TextViewColumn('unique_drugs', 'Unique Drugs', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for unique_events field
            //
            $column = new TextViewColumn('unique_events', 'Unique Events', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for vocab_name field
            //
            $column = new TextViewColumn('drug_vocabulary_vocab_name', 'Drug Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for event_vocab_name field
            //
            $column = new TextViewColumn('event_vocabulary_event_vocab_name', 'Event Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for published field
            //
            $column = new CheckboxViewColumn('published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $column->setDisplayValues($this->RenderText('<span class="pg-row-checkbox checked"></span>'), $this->RenderText('<span class="pg-row-checkbox"></span>'));
            $grid->AddPrintColumn($column);
            
            //
            // View column for curator_name field
            //
            $column = new TextViewColumn('curator_name', 'Curator Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for curator_email field
            //
            $column = new TextViewColumn('curator_email', 'Curator Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for upload_date field
            //
            $column = new DateTimeViewColumn('upload_date', 'Upload Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for dataset_id field
            //
            $column = new TextViewColumn('dataset_id', 'Dataset Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for dataset_name field
            //
            $column = new TextViewColumn('dataset_name', 'Dataset Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for authors field
            //
            $column = new TextViewColumn('authors', 'Authors', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for unique_drugs field
            //
            $column = new TextViewColumn('unique_drugs', 'Unique Drugs', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for unique_events field
            //
            $column = new TextViewColumn('unique_events', 'Unique Events', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for vocab_name field
            //
            $column = new TextViewColumn('drug_vocabulary_vocab_name', 'Drug Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for event_vocab_name field
            //
            $column = new TextViewColumn('event_vocabulary_event_vocab_name', 'Event Vocabulary', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for published field
            //
            $column = new CheckboxViewColumn('published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $column->setDisplayValues($this->RenderText('<span class="pg-row-checkbox checked"></span>'), $this->RenderText('<span class="pg-row-checkbox"></span>'));
            $grid->AddExportColumn($column);
            
            //
            // View column for curator_name field
            //
            $column = new TextViewColumn('curator_name', 'Curator Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for curator_email field
            //
            $column = new TextViewColumn('curator_email', 'Curator Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for upload_date field
            //
            $column = new DateTimeViewColumn('upload_date', 'Upload Date', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        public function dataset_file_GenerateFileName_edit(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        public function dataset_file_GenerateFileName_insert(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        
        public function ShowEditButtonHandler(&$show)
        {
            if ($this->GetRecordPermission() != null)
                $show = $this->GetRecordPermission()->HasEditGrant($this->GetDataset());
        }
        
        public function ShowDeleteButtonHandler(&$show)
        {
            if ($this->GetRecordPermission() != null)
                $show = $this->GetRecordPermission()->HasDeleteGrant($this->GetDataset());
        }
        
        public function GetModalGridDeleteHandler() { return 'dataset_modal_delete'; }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'datasetGrid');
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetInsertClientEditorValueChangedScript($this->RenderText('if (sender.getFieldName() == \'published\') {
              //editors[\'authors\'].setValue(editors[\'authors\'].sender.getValue());
              if (sender.getValue() == 1) {
                editors[\'journal_id\'].visible(true);
                editors[\'paper_title\'].visible(true);
                editors[\'paper_authros\'].visible(true);
                editors[\'pubmed_id\'].visible(true);
                editors[\'doi\'].visible(true);
              } else {
                if (sender.getValue() == 0) {
                    editors[\'journal_id\'].visible(false);
                    editors[\'paper_title\'].visible(false);
                    editors[\'paper_authros\'].visible(false);
                    editors[\'pubmed_id\'].visible(false);
                    editors[\'doi\'].visible(false); 
                    editors[\'journal_id\'].setValue(\'\');
                    editors[\'paper_title\'].setValue(\'\');
                    editors[\'paper_authros\'].setValue(\'\');
                    editors[\'pubmed_id\'].setValue(\'\');
                    editors[\'doi\'].setValue(\'\');              
                }
              }
            }'));
            
            $result->SetEditClientEditorValueChangedScript($this->RenderText('if (sender.getFieldName() == \'published\') {
              //editors[\'authors\'].setValue(editors[\'authors\'].sender.getValue());
              if (sender.getValue() == 1) {
                editors[\'journal_id\'].visible(true);
                editors[\'paper_title\'].visible(true);
                editors[\'paper_authros\'].visible(true);
                editors[\'pubmed_id\'].visible(true);
                editors[\'doi\'].visible(true);
              } else {
                if (sender.getValue() == 0) {
                    editors[\'journal_id\'].visible(false);
                    editors[\'paper_title\'].visible(false);
                    editors[\'paper_authros\'].visible(false);
                    editors[\'pubmed_id\'].visible(false);
                    editors[\'doi\'].visible(false); 
                    editors[\'journal_id\'].setValue(\'\');
                    editors[\'paper_title\'].setValue(\'\');
                    editors[\'paper_authros\'].setValue(\'\');
                    editors[\'pubmed_id\'].setValue(\'\');
                    editors[\'doi\'].setValue(\'\');              
                }
              }
            }'));
            
            $result->SetEditClientFormLoadedScript($this->RenderText('  if (editors[\'published\'].getValue() == 1) {
                editors[\'journal_id\'].visible(true);
                editors[\'paper_title\'].visible(true);
                editors[\'paper_authros\'].visible(true);
                editors[\'pubmed_id\'].visible(true);
                editors[\'doi\'].visible(true);
              } else {
                if (editors[\'published\'].getValue() == 0) {
                    editors[\'journal_id\'].visible(false);
                    editors[\'paper_title\'].visible(false);
                    editors[\'paper_authros\'].visible(false);
                    editors[\'pubmed_id\'].visible(false);
                    editors[\'doi\'].visible(false); 
                    //editors[\'journal_id\'].setValue(\'\');
                    //editors[\'paper_title\'].setValue(\'\');
                    //editors[\'paper_authros\'].setValue(\'\');
                    //editors[\'pubmed_id\'].setValue(\'\');
                    //editors[\'doi\'].setValue(\'\');              
                }
              }'));
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setTableBordered(true);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->CreateGridSearchControl($result);
            $this->CreateGridAdvancedSearchControl($result);
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
            $this->SetShowPageList(true);
            $this->SetSimpleSearchAvailable(true);
            $this->SetAdvancedSearchAvailable(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv','pdf'));
    
            //
            // Http Handlers
            //
            //
            // View column for dataset_name field
            //
            $column = new TextViewColumn('dataset_name', 'Dataset Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_dataset_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for authors field
            //
            $column = new TextViewColumn('authors', 'Authors', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_authors_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for curator_name field
            //
            $column = new TextViewColumn('curator_name', 'Curator Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_curator_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for curator_email field
            //
            $column = new TextViewColumn('curator_email', 'Curator Email', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_curator_email_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for dataset_name field
            //
            $column = new TextViewColumn('dataset_name', 'Dataset Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_dataset_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for authors field
            //
            $column = new TextViewColumn('authors', 'Authors', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_authors_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for paper_title field
            //
            $column = new TextViewColumn('paper_title', 'Paper Title', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_paper_title_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for paper_authros field
            //
            $column = new TextViewColumn('paper_authros', 'Paper Authros', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_paper_authros_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for curator_name field
            //
            $column = new TextViewColumn('curator_name', 'Curator Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_curator_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for curator_email field
            //
            $column = new TextViewColumn('curator_email', 'Curator Email', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'datasetGrid_curator_email_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
        
        public function OpenAdvancedSearchByDefault()
        {
            return false;
        }
    
        protected function DoGetGridHeader()
        {
            return '';
        }
    }

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new datasetPage("dataset.php", "dataset", GetCurrentUserGrantForDataSource("dataset"), 'UTF-8');
        $Page->SetTitle('Drug-Drug Interaction Dataset Upload');
        $Page->SetMenuLabel('Published Dataset Upload');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("dataset"));
        GetApplication()->SetEnableLessRunTimeCompile(GetEnableLessFilesRunTimeCompilation());
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e->getMessage());
    }
	

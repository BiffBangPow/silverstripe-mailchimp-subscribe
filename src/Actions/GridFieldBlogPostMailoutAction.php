<?php

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_ActionMenuItem;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\Forms\GridField\GridField_ActionProvider;
use SilverStripe\Forms\GridField\GridField_FormAction;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\ValidationException;

class GridFieldBlogPostMailoutAction implements GridField_ColumnProvider, GridField_ActionProvider
{

    public function augmentColumns($gridField, &$columns)
    {
        if(!in_array('Actions', $columns)) {
            $columns[] = 'Actions';
        }
    }

    public function getTitle($gridField, $record)
    {
        return _t(__CLASS__ . '.Send Mailout', "Send Mailout");
    }

    public function getGroup($gridField, $record)
    {
        return GridField_ActionMenuItem::DEFAULT_GROUP;
    }

    public function getExtraData($gridField, $record, $columnName)
    {
        if ($gridField) {
            return $gridField->getAttributes();
        }

        return null;
    }

    public function getColumnAttributes($gridField, $record, $columnName)
    {
        return ['class' => 'grid-field__col-compact'];
    }

    public function getColumnMetadata($gridField, $columnName)
    {
        if($columnName == 'Actions') {
            return ['title' => ''];
        }
    }

    public function getColumnsHandled($gridField)
    {
        return ['Actions'];
    }

    public function getColumnContent($gridField, $record, $columnName)
    {
        if(!$record->canEdit()) return;

        $field = GridField_FormAction::create(
            $gridField,
            'CustomAction'.$record->ID,
            'Send Mailout',
            "sendmailout",
            ['RecordID' => $record->ID]
        )->addExtraClass('action action-detail btn btn-primary font-icon-page-multiple');

        return $field->Field();
    }

    public function getActions($gridField)
    {
        return ['sendmailout'];
    }

    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if($actionName == 'sendmailout') {
            // perform your action here

            /** @var BlogPost $item */
            $item = $gridField->getList()->byID($arguments['RecordID']);

            if (!$item) {
                return;
            }

            if ($item->ClassName !== BlogPost::class) {
                throw new ValidationException('Can only be used to send mailouts about Blog Posts');
            }

            // todo add send mailout code here

            // output a success message to the user
            Controller::curr()->getResponse()->setStatusCode(
                200,
                'Blog post mailout sent.'
            );
        }
    }
}
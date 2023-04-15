<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Entity;
use App\Models\EntityRecognition;
use App\Models\GeneratedText;
use App\Models\Label;
use App\Models\Labeling;
use App\Models\LabelSet;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Project;
use App\Models\Sample;
use App\Models\SampleText;

use function PHPUnit\Framework\returnSelf;

class SeederController extends Controller
{
    public function users()
    {
        User::create([
            "name" => "Ngo Quoc Toai",
            "email" => "ngotoai@email.com",
            "password" => bcrypt("123123"),
            "role" => "manager"
        ]);
        User::create([
            "name" => "Le Huynh Minh Tuan",
            "email" => "tuanrose@email.com",
            "password" => bcrypt("123123"),
            "role" => "manager"
        ]);
        User::create([
            "name" => "Pham Minh Tri",
            "email" => "hanoinhoem@email.com",
            "password" => bcrypt("123123"),
            "role" => "manager"
        ]);
        User::create([
            "name" => "Ngo Quoc Toai",
            "email" => "ngotoai2@email.com",
            "password" => bcrypt("123123"),
            "role" => "annotator"
        ]);
        User::create([
            "name" => "Le Huynh Minh Tuan",
            "email" => "tuanrose2@email.com",
            "password" => bcrypt("123123"),
            "role" => "annotator"
        ]);
        User::create([
            "name" => "Pham Minh Tri",
            "email" => "hanoinhoem2@email.com",
            "password" => bcrypt("123123"),
            "role" => "annotator"
        ]);

        return response(['message' => 'Oke']);
    }

    public function textClassification()
    {
        $user_toai = User::where('email', 'ngotoai2@email.com')->first();
        $user_tri = User::where('email', 'hanoinhoem2@email.com')->first();

        $project = Project::create([
            'name' => 'Text Classification 1',
            'description' => 'abc',
            'has_label_sets' => true,
            'has_entity_recognition' => false,
            //
            // always
            'number_of_texts' => 1,
            'text_titles' => 'Text',
            // 
            // if true
            'has_generated_text' => false,
            'number_of_generated_texts' => null,
            'maximum_of_generated_texts' => null,
            'generated_text_titles' => '',
            //
            'maximum_performer' => 1,
        ]);

        Assignment::create([
            'project_id' => $project->id,
            'user_id' => $user_toai->id
        ]);
        Assignment::create([
            'project_id' => $project->id,
            'user_id' => $user_tri->id
        ]);

        $label_set = LabelSet::create([
            'pick_one' => true,
            'project_id' => $project->id,
        ]);
        $positive_label = Label::create([
            'label' => 'Positive',
            'label_set_id' => $label_set->id,
        ]);
        $negative_label = Label::create([
            'label' => 'Negative',
            'label_set_id' => $label_set->id,
        ]);

        $sample = Sample::create([
            'project_id' => $project->id
        ]);
        SampleText::create([
            'text' => "That was great",
            'sample_id' => $sample->id,
        ]);


        Labeling::create([
            'label_id' => $positive_label->id,
            'sample_id' => $sample->id,
            'performer_id' => $user_toai->id,
        ]);

        $sample = Sample::create([
            'project_id' => $project->id
        ]);
        SampleText::create([
            'text' => "Holly shit",
            'sample_id' => $sample->id,
        ]);
        Labeling::create([
            'label_id' => $negative_label->id,
            'sample_id' => $sample->id,
            'performer_id' => $user_toai->id,
        ]);

        $sample = Sample::create([
            'project_id' => $project->id
        ]);
        SampleText::create([
            'text' => "Mot ngay dep troi",
            'sample_id' => $sample->id,
        ]);
        Labeling::create([
            'label_id' => $negative_label->id,
            'sample_id' => $sample->id,
            'performer_id' => $user_toai->id,
        ]);

        return response(['message' => 'Oke']);
    }

    public function machineTranslation()
    {
        $user_tuan = User::where('email', 'tuanrose2@email.com')->first();
        $user_toai = User::where('email', 'ngotoai2@email.com')->first();

        $project = Project::create([
            'name' => 'Machine Translation 1',
            'description' => 'abc',
            'has_label_sets' => false, // translation => false
            'has_entity_recognition' => false,
            //
            // always
            'number_of_texts' => 1,
            'text_titles' => 'English',
            // 
            // Translation -> true
            'has_generated_text' => true,
            'number_of_generated_texts' => 1,
            'maximum_of_generated_texts' => null,
            'generated_text_titles' => 'Vietnamese',
            //
            'maximum_performer' => 3,
        ]);

        Assignment::create([
            'project_id' => $project->id,
            'user_id' => $user_tuan->id,
        ]);
        Assignment::create([
            'project_id' => $project->id,
            'user_id' => $user_toai->id,
        ]);

        $sample = Sample::create([
            'project_id' => $project->id,
        ]);

        $sample_text = SampleText::create([
            'sample_id' => $sample->id,
            'text' => 'Banana'
        ]);

        $generated_text = GeneratedText::create([
            'sample_id' => $sample->id,
            'text' => 'Chuối',
            'performer_id' => $user_tuan->id
        ]);

        return response(['message' => 'Oke']);
    }

    public function entityRecognition()
    {
        $user_tri = User::where('email', 'hanoinhoem2@email.com')->first();
        $user_tuan = User::where('email', 'tuanrose2@email.com')->first();

        $project = Project::create([
            'name' => 'Entity Recognition 1',
            'description' => 'abc',
            'has_label_sets' => false, // entity -> false
            'has_entity_recognition' => true, // entity
            //
            // always
            'number_of_texts' => 1,
            'text_titles' => 'Entity test text',
            // 
            // Not translation -> false
            'has_generated_text' => false,
            'number_of_generated_texts' => null,
            'maximum_of_generated_texts' => null,
            'generated_text_titles' => '',
            //
            'maximum_performer' => 1,
        ]);

        Assignment::create([
            'project_id' => $project->id,
            'user_id' => $user_tri->id,
        ]);
        Assignment::create([
            'project_id' => $project->id,
            'user_id' => $user_tuan->id,
        ]);

        $entity_cat = Entity::create([
            'name' => 'Cat',
            'project_id' => $project->id
        ]);

        $entity_dog = Entity::create([
            'name' => 'Dog',
            'project_id' => $project->id
        ]);

        $sample = Sample::create([
            'project_id' => $project->id
        ]);

        $sample_text = SampleText::create([
            'sample_id' => $sample->id,
            'text' => 'Information of Dog and Cat'
        ]);

        EntityRecognition::create([
            'sample_text_id' => $sample_text->id,
            'entity_id' => $entity_cat->id,
            'start' => 23,
            'end' => 25,
            'performer_id' => $user_tri->id
        ]);

        EntityRecognition::create([
            'sample_text_id' => $sample_text->id,
            'entity_id' => $entity_dog->id,
            'start' => 15,
            'end' => 17,
            'performer_id' => $user_tri->id
        ]);

        return response(['message' => 'Oke']);
    }
}

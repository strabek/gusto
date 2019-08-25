<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/recipe/{id}", name="recipe_by_id", methods={"GET"})
     */
    public function recipeByIdAction(Request $request, $id)
    {
        // Filter data
        $filtered = array_values($this->filterData($this->readDataFile(), 'id', $id));
        
        $response = new JsonResponse();
        $response->setData($filtered);
        
        return $response;
    }

    /**
     * @Route("/recipe/cuisine/{cuisineName}", name="recipe_by_cuisine", methods={"GET"})
     */
    public function recipeByCuisineAction(Request $request, $cuisineName)
    {
        // Read data file
        $data = $this->readDataFile();

        // Filter data
        $filtered = array_values($this->filterData($this->readDataFile(), 'recipe_cuisine', $cuisineName));
        
        $remove = ["created_at","updated_at","box_type","slug","short_title","calories_kcal","protein_grams","fat_grams","carbs_grams","bulletpoint1","bulletpoint2","bulletpoint3","recipe_diet_type_id","season","base","protein_source","preparation_time_minutes","shelf_life_days","equipment_needed","origin_country","recipe_cuisine","in_your_box","gousto_reference"];

        $filteredFields = [];
        foreach ($filtered as $value) {
            $item = array_diff_key($value, array_flip($remove));
            array_push($filteredFields, $item);
        }

        $response = new JsonResponse();
        $response->setData($filteredFields);
        
        return $response;
    }

    /**
     * @Route("/recipe/{id}", name="recipe_update", methods={"PATCH"})
     */
    public function recipeUpdateAction(Request $request, $id)
    {
        // Read data file
        $data = $this->readDataFile();
        
        // Filter data
        $filtered = $this->filterData($this->readDataFile(), 'id', $id);

        $csvKey = array_keys($filtered);
        $filtered = array_values($filtered)[0];

        
        // Set allowed fields
        $allowedFields = ["box_type","title","slug","short_title","marketing_description","calories_kcal","protein_grams","fat_grams","carbs_grams","bulletpoint1","bulletpoint2","bulletpoint3","recipe_diet_type_id","season","base","protein_source","preparation_time_minutes","shelf_life_days","equipment_needed","origin_country","recipe_cuisine","in_your_box","gousto_reference"];

        $requestFields = json_decode($request->getContent(), true);

        foreach ($requestFields as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $filtered[$key] = $value;
            }
        }

        // Update updated_at field
        $filtered['updated_at'] = date('d/m/Y h:i:s');

        $data[$csvKey[0]] = $filtered;

        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        file_put_contents(
            'recipe-data.csv',
            $serializer->encode($data, 'csv')
        );

        $response = new JsonResponse();
        $response->setData($filtered);
        
        return $response;
    }

    private function readDataFile()
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        
        // Read data file
        return $serializer->decode(file_get_contents('recipe-data.csv'), 'csv');
    }

    private function filterData($data, $k, $v)
    {
        return array_values(array_filter(
            $data,
            function ($val, $key) use ($k, $v) {
                return $val[$k] == $v;
            },
            ARRAY_FILTER_USE_BOTH
        ));
    }
}

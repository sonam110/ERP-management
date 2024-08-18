<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetMaster;
class AssetMasterController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage branch'))
        {
            $assetMasters = AssetMaster::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('assetMaster.index', compact('assetMasters'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create branch'))
        {
            return view('assetMaster.create');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create branch'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'price' => 'required|numeric|min:0',
                                   'available_quantity' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $unique_id = \Str::random(6);
            $assetMaster             = new AssetMaster();
            $assetMaster->name       = $request->name;
            $assetMaster->unique_id       = $unique_id;
            $assetMaster->price       = $request->price;
            $assetMaster->available_quantity       = $request->available_quantity;
            $assetMaster->purchase_date       = $request->purchase_date;
            $assetMaster->supported_date       = $request->supported_date;
            $assetMaster->description       = $request->description;
            $assetMaster->created_by = \Auth::user()->creatorId();
            $assetMaster->save();


            return redirect()->route('assetmaster.index')->with('success', __('Asset Master successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(AssetMaster $assetmaster)
    {
        return redirect()->route('assetmaster.index');
    }

    public function edit(AssetMaster $assetmaster)
    {
        if(\Auth::user()->can('edit branch'))
        {
            if($assetmaster->created_by == \Auth::user()->creatorId())
            {

                return view('assetmaster.edit', compact('assetmaster'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, AssetMaster $assetmaster)
    {
        if(\Auth::user()->can('edit branch'))
        {
            if($assetmaster->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                   'name' => 'required',
                                   'price' => 'required|numeric|min:0',
                                   'available_quantity' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }


                $assetmaster->name       = $request->name;
                $assetmaster->price       = $request->price;
                $assetmaster->available_quantity       = $request->available_quantity;
                $assetmaster->purchase_date       = $request->purchase_date;
                $assetmaster->purchase_date       = $request->purchase_date;
                $assetmaster->description       = $request->description;
                $assetmaster->save();

                return redirect()->route('assetmaster.index')->with('success', __('Asset master successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(AssetMaster $assetmaster)
    {
        
        if(\Auth::user()->can('delete branch'))
        {
            if($assetmaster->created_by == \Auth::user()->creatorId())
            {
                $assetmaster->delete();

                return redirect()->route('assetmaster.index')->with('success', __('Asset master successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}

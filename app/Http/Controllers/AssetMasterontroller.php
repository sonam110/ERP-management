<?php

namespace App\Http\Controllers;

use App\Models\AssetMaster;
use App\Models\Department;
use Illuminate\Http\Request;

class AssetMasterontroller extends Controller
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
                                   'price' => 'required',
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
            $assetMaster->description       = $request->description;
            $assetMaster->created_by = \Auth::user()->creatorId();
            $assetMaster->save();

            return redirect()->route('assetMaster.index')->with('success', __('AssetMaster  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(AssetMaster $assetMaster)
    {
        return redirect()->route('assetMaster.index');
    }

    public function edit(AssetMaster $assetMaster)
    {
        if(\Auth::user()->can('edit branch'))
        {
            if($assetMaster->created_by == \Auth::user()->creatorId())
            {

                return view('assetMaster.edit', compact('assetMaster'));
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

    public function update(Request $request, AssetMaster $assetMaster)
    {
        if(\Auth::user()->can('edit branch'))
        {
            if($assetMaster->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                       'price' => 'required',
                                       'available_quantity' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $assetMaster->name       = $request->name;
                $assetMaster->unique_id       = $unique_id;
                $assetMaster->price       = $request->price;
                $assetMaster->available_quantity       = $request->available_quantity;
                $assetMaster->description       = $request->description;
                $assetMaster->save();

                return redirect()->route('assetMaster.index')->with('success', __('Asset Master successfully updated.'));
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

    public function destroy(AssetMaster $assetMaster)
    {
        if(\Auth::user()->can('delete branch'))
        {
            if($assetMaster->created_by == \Auth::user()->creatorId())
            {
                $assetMaster->delete();

                return redirect()->route('assetMaster.index')->with('success', __('Asset Master successfully deleted.'));
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

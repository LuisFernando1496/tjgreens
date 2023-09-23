<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use App\Rol;
use App\Address;
use App\Client;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->rol_id == 1) {
             $users = User::with('address')->where('status', 1)->where('id', '!=',$user->id)->get(); 
             $offices = BranchOffice::where('status', true)->get();
             $rols =  Rol::all();
            return view('user.index', ['users' => $users, 'offices' => $offices, 'rols' =>$rols]);
        }
        if($user->rol_id == 3 || $user->rol_id == 5)
        {
            $branch_id = $user->branch_office_id;
            $users = User::with('address')->where('id', '!=',$user->id)
            ->where('branch_office_id', $branch_id)
            ->where('rol_id', '!=', 1)
            ->where('status', true)
            ->get(); 
            $offices = BranchOffice::where('status', true)->where('id',$branch_id)->get();
            $rols =  Rol::all();
         //   return $offices;
           return view('user.index', ['users' => $users, 'offices' => $offices, 'rols' =>$rols]);
        } 
        else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    public function cambiarPass(){
        $user = User::first();
        //return $user;
        DB::beginTransaction();
        try {
            $user->password = "Pass1234";
            $user->update();
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            return 0;
        }
    }

    public function ajaxget()
    {
        $user = Auth::user();
        $rol = $user->rol_id;
        if ($user->rol_id == 1 || $user->rol_id == 3) {
            $users = User::with(['address','branchOffice','rol'])->where('status',1)->where('id','!=',$user->id)->get();
            return response()->json([
                'users' => $users,
                'rol' => $rol,
                'rolname' => $user->rol->name
            ]);
        } else {
            return response()->json([
                'error' => "No tienes permisos"
            ]);
        }

    }

    public function indexClient()
    {
        $user = Auth::user();
        if ($user->rol_id == 1 || $user->rol_id == 3) {
            $last = Client::latest()->first();
            return view('user.indexClient', [
                'users' => Client::where('status', 1)->orderBy('id','desc')->get(),
                //'last' => $last->id
                'last' => $last
            ]);
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }
    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return (new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options))->withPath(url()->current());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $address = new Address();
        $current_user = Auth::user();
        if ($current_user->rol_id == 1 || $current_user->rol_id == 3 ||  $current_user->rol_id == 5) {
            $rols = Rol::all();
            $branchOffices = BranchOffice::all();
            return view('', ['user' => $user, 'rols' => $rols, 'branchOffices' => $branchOffices, 'address' => $address]);
        } elseif ($current_user->rol_id == 2) {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return back()->withErrors(["error" => "Limite de usuarios alcanzado contactar con soporte tecnico"]);
        DB::beginTransaction();
        try {
            if ($request->has('branch_office_id')) {
                $request['address_id'] = Address::create($request->only(['street', 'suburb', 'postal_code', 'city', 'state', 'country', 'ext_number', 'int_number']))->id;
                // $request['user_id'] = 1;
                $request['user_id'] = auth()->user()->id;
                // $request['branch_office_id'] = auth()->user()->branchOffice->id;
                User::create($request->only(['address_id', 'branch_office_id', 'rol_id', 'phone', 'password', 'email', 'curp', 'rfc', 'last_name', 'name', 'user_id']));
            } else {
                $request['user_id'] = auth()->user()->id;
                $client = new Client($request->all());
                $client->save();
            }
            DB::commit();
            return back()->with(["success" => "Éxito al realizar la operación."]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            //return $th->getMessage();
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'street' => 'required',
                'suburb' => 'required',
                'postal_code' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'name' => 'required',
                'last_name' => 'required',
                'rfc' => 'required',
                'curp' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'ext_number' => 'required',
                'int_number' => 'required',
            ]);
            $user->address->edit($request->only(['street', 'suburb', 'postal_code', 'city', 'state', 'country']));
            $user->edit($request->only(['address_id', 'branch_office_id', 'rol_id', 'phone', 'email', 'curp', 'rfc', 'last_name', 'name']));
            DB::commit();
            return back()->with(["success" => "Éxito al realizar la operación."]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            //return $th->getMessage();
            //throw $th;
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3 || Auth::user()->rol_id == 5) {
            DB::beginTransaction();
            try {
                if ($request->has('branch_office_id')) {
                    $user->address->edit($request->only(['street', 'suburb', 'postal_code', 'city', 'state', 'country']));
                    $user->edit($request->only(['address_id', 'branch_office_id', 'rol_id', 'phone', 'email', 'curp', 'rfc', 'last_name', 'name']));
                } else {
                    $client = Client::findOrFail($request['client_id']);
                    $client->update($request->all());
                }
                DB::commit();
                return back()->with(["success" => "Éxito al realizar la operación."]);
            } catch (\Throwable $th) {
                return $th;
                DB::rollback();
                return back()->withErrors(["error" => "No se pudo realizar la operación."]);
                //return $th->getMessage();
                //throw $th;
            }
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    public function checkAdmin(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'The provided credentials are incorrect'], 404);
        }
        if ($user->rol_id == 1 || $user->rol_id == 2 || $user->rol_id == 3) {
            return response()->json(['success' => true, 'user' => $user], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'You are not admin or manager'], 401);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,User $user)
    {
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3 || Auth::user()->rol_id == 5) {
            if ($request->has('eliminate_client')) {
                $client = Client::findOrFail($request['eliminate_client']);
                $client->changeStatus(false);
            } else {
                $user->changeStatus(false);
            }
            return back()->with(["success" => "Éxito al realizar la operación."]);
        } else {
            return back()->withErrors(["error" => "No tienes permisos"]);
        }
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        DB::beginTransaction();
        try {
            $request['password'] = $request->newPass;
            $user->update($request->only(['password']));
            DB::commit();
            return back()->with(["success" => "Éxito al realizar la operación."]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(["error" => "No se pudo realizar la operación."]);
            //return $th->getMessage();
            //throw $th;
        }
        //
    }
}

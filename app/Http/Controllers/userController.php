<?php 

	namespace socialwall\Http\Controllers;
	use Illuminate\Http\Request;
	use socialwall\User;
	use Illuminate\Support\Facades\Auth;
	use Session;
	use View;
	use Input;	
	use Validator;

	class userController extends Controller {	

		 public function index() {

        $users = User::all();

        // return view('userIndex', ['users' => $users]);

        return View::make('userIndex')
            ->with('users', $users);
    }

    public function create() {
    	
    	// return view('userCreate');
    	return View::make('userCreate');
    }

    public function store(Request $request) {

			$this -> validate($request, [
				'email' => 'required|email|unique:users',
				'username' => 'required|max:80',
				'password' => 'required|min:6',
			]);

			if($request['admin']) {

			  $userIsAdmin = true;
			}
			else {

			  $userIsAdmin = false;  
			}

			$email = $request['email'];
			$username = $request['username'];
			$password = bcrypt($request['password']);
			$admin = $userIsAdmin;

			$user = new User();
			$user -> email = $email;
			$user -> username = $username;
			$user -> password = $password;
			$user -> admin = $userIsAdmin;

			$user -> save();

			Session::flash('message', 'Successfully created new user!');

			return redirect() -> route('dashboard');
		}
    
    public function edit($id) {

    	$user = User::find($id);

    	 // return view('userEdit', ['user' => $user]);
    	return View::make('userEdit')
            ->with('user', $user);
    }

     public function show($id)
    {
        
        $user = User::find($id);

        // return view('userIndex', ['user' => $user]);

        return View::make('userShow')
            ->with('user', $user);
        
    }

    public function update($id) {

			$rules = [
				'email' => 'required|email|unique:users',
				'username' => 'required|max:80',
				'password' => 'required|min:6',
			];
            
        $validator = Validator::make(Input::all(), $rules);


        if(Input::get('admin')) {

			  $userIsAdmin = true;
			}
			else {

			  $userIsAdmin = false;  
			}

        $user = [
				Input::get('email'),
				Input::get('username'),
				Input::get('password')
        ];

        if ($validator->fails()) {

        	// return View::make('userEdit')
         //    ->with('user', $user);
         //    // return View::make('userEdit')
         //    //     ->withErrors($validator)
         //    //     ->withInput(Input::except('password'));
        } else {
           
            $user = User::find($id);
            $user->username       = Input::get('username');
            $user->email      = Input::get('email');
            $user->password = bcrypt(Input::get('password'));
            $user->admin = $userIsAdmin;

            $user->save();

            Session::flash('message', 'Successfully updated user!');
            return redirect() -> route('dashboard');
        }
		}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {

    	$user = User::find($id);
      $user->delete();

      
      Session::flash('message', 'Successfully deleted the user!');
      return redirect() -> route('user');
    }


		public function dashboard() {

			return view('dashboard');
		}

		public function addUser() {

			if(Auth::user()->isAdmin()) {

				return view('signUp');
			}
			else {

				return redirect() -> back();
			}
		}

		// public function signUp(Request $request) {

		// 	$this -> validate($request, [
		// 		'email' => 'required|email|unique:users',
		// 		'username' => 'required|max:80',
		// 		'password' => 'required|min:6',
		// 	]);

		// 	if($request['admin']) {

		// 	  $userIsAdmin = true;
		// 	}
		// 	else {

		// 	  $userIsAdmin = false;  
		// 	}

		// 	$email = $request['email'];
		// 	$username = $request['username'];
		// 	$password = bcrypt($request['password']);
		// 	$admin = $userIsAdmin;

		// 	$user = new User();
		// 	$user -> email = $email;
		// 	$user -> username = $username;
		// 	$user -> password = $password;
		// 	$user -> admin = $userIsAdmin;

		// 	$user -> save();

		// 	Auth::login($user);

		// 	return redirect() -> route('dashboard');
		// }

		public function signIn(Request $request) {

			$this -> validate($request, [
				'username' => 'required',
				'password' => 'required'
			]);

			if(Auth::attempt(['username' => $request['username'], 'password' => $request['password']])) {

				return redirect() -> route('dashboard');
			}
			else {

				return redirect() -> back();
			}
		}

		// public function getAllUsers()  {

		// 	$users = DB::table('users')->get();

  //     return view('deleteUser', ['users' => $users]);
		// }

		public function deleteUser()  {

			 // $user = DB::table('users')->where('id', $id)->first();
			 echo 'dddd';
			 // $user->delete();

    //   return view('dashboard');
		}

	}
?>

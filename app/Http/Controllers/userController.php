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

		public function __construct() {

        $this->middleware('auth', ['except' => ['signIn']]);

        $this->middleware('admin', ['except' => ['dashboard', 'signIn', 'update', 'edit']]);

        $this->middleware('before', ['only' => ['destroy']]);

        $this->middleware('after', ['except' => ['dashboard', 'signIn', 'index']]);
    }

		public function dashboard() {

			return view('dashboard');
		}

		public function signIn(Request $request) {

			$this -> validate($request, [
				'username' => 'required',
				'password' => 'required|min:6'
			]);

			if(Auth::attempt(['username' => $request['username'], 'password' => $request['password']])) {

				return redirect() -> route('dashboard');
			}
			else {

				return redirect() -> back()->withInput()->with('message', 'Login Attempt Failed! Incorrect username or password');
			}
		}

		public function index() {

      $users = User::all();

      return View::make('userIndex')
        ->with('users', $users);
    }

    public function create() {
    	
    	return view('userCreate');
    }

    public function store(Request $request) {

			$this -> validate($request, [
				'email' => 'required|email|unique:users',
				'username' => 'required|max:80|unique:users',
				'password' => 'required|min:6'
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

			Session::flash('message', 'You have successfully created a new user.');

			return redirect() -> action('userController@index');
		}
    
    public function edit($id) {

    	$user = User::find($id);

    	return View::make('userEdit')
        ->with('user', $user);
    }

    public function update($id) {

			$request = Input::all();
			$user = User::find($id);

			$rules = [
				'email' => 'required|email|unique:users,email,'. $id,
				'username' => 'required|max:80|unique:users,username,' .$id,
				'password' => 'required|min:6'
			];
            
      $validator = Validator::make($request, $rules);


      if(array_key_exists('admin', $request)) {

		  	$userIsAdmin = true;
			}
			else {

			  $userIsAdmin = false;  
			}

			$email = $request['email'];
			$username = $request['username'];
			$password = bcrypt($request['password']);
			$admin = $userIsAdmin;


      if ($validator->fails()) {

        return View::make('userEdit')
          ->withErrors($validator)
          ->with([
          	'user' => $user,
          	'request' => $request
          	]);         
          
      } 
      else {
         
				$user -> email = $email;
				$user -> username = $username;
				$user -> password = $password;
				$user -> admin = $userIsAdmin;

				$user -> save();

        Session::flash('message', 'You have successfully updated the user information.');

        return redirect() -> action('userController@index');
      }
		}

    public function destroy($id) {

    	$user = User::find($id);
      $user -> delete();

      Session::flash('message', 'You have successfully deleted this user!');

      return redirect() -> action('userController@index');
    }
	}
?>

<?php

namespace App\Modules\MITOCW\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Modules\Home\Models\ContactModel;

class ExtractController extends Controller
{
    protected $fields = [
        'name',
        'email',
        'subject',
        'message',
    ];

    public function importVideos(Request $request)
    {


        $mitModel = new MITOCWModel();


        return response()->json(['status' => 1, 'messages' => 'Message recorded.'], 200);
    }

    public function importTracks(Request $request)
    {
        $physicsTracks = [
            'Classical Mechanics' => [
                'Vectors' => [
                    'tracks' => [
                        [
                            'name' => 'Vectors',
                            'lessons' => [
                                [
                                    'name' => 'Vectors vs. Scalars',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L00v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Vector Operators',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L00v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Coordinate Systems and Unit Vectors',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L00v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Vectors - Magnitude and Direction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L00v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Vector Decomposition into Components',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L00v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Going Between Representations',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L00v06_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Kinematics' => [
                    'tracks' => [
                        [
                            'name' => 'Kinematics',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W01Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => '1D Kinematics - Position and Velocity',
                            'lessons' => [
                                [
                                    'name' => 'Coordinate Systems and Unit Vectors in 1D Position Vector in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L01v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Position Vector in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L01v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Displacement Vector in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L01v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Average Velocity in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L01v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Instantaneous Velocity in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L01v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Derivatives',
                                    'url' => 'http://www.archive.org/download/MIT18.01JF07/ocw-18.01-f07-lec03_300k.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Derivatives in Kinematics',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L01v06_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => '1D Kinematics - Acceleration',
                            'lessons' => [
                                [
                                    'name' => 'Introduction to Acceleration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L02v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Acceleration in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L02v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Acceleration from Position',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L02v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Integration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L02v04_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => '2D Kinematics - Position, Velocity, and Acceleration',
                            'lessons' => [
                                [
                                    'name' => 'Coordinate System and Position Vector in 2D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L03v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Instantaneous Velocity in 2D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L03v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Instantaneous Acceleration in 2D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L03v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Projectile Motion',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L03v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Demos for Projectile Motion 1',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_Demo_01_360p.mp4',
                                ],
                                [
                                    'name' => 'Demos for Projectile Motion 2',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_Demo_02_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Newton\'s Laws' => [
                    'tracks' => [
                        [
                            'name' => 'Newton\'s Laws',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W02Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Newton\'s Laws of Motion',
                            'lessons' => [
                                [
                                    'name' => 'Newton\'s First and Second Laws',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L04v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Newton\'s Third Law',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L04v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Reference Frames',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L04v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Non-inertial Reference Frames',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L04v04_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Gravity',
                            'lessons' => [
                                [
                                    'name' => 'Universal Law of Gravitation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L05v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example: Gravity Superposition',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L05v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Gravity at the Surface of the Earth: The Value of g.',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L05v03_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Contact Forces',
                            'lessons' => [
                                [
                                    'name' => 'Contact Forces',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L06v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Static Friction Lesson',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L06v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Friction at the Nanoscale',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_Friction_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Tension and Springs',
                            'lessons' => [
                                [
                                    'name' => 'Pushing Pulling and Tension',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L07v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Ideal Rope',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L07v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Solving Pulley Systems',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L07v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Hooke\'s Law',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L07v04_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Circular Motion' => [
                    'tracks' => [
                        [
                            'name' => 'Circular Motion',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W03Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Circular Motion - Position and Velocity',
                            'lessons' => [
                                [
                                    'name' => 'Polar Coordinates',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L08v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Circular Motion: Position and Velocity Vectors',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L08v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Angular Velocity',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L08v03_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Uniform Circular Motion',
                            'lessons' => [
                                [
                                    'name' => 'Uniform Circular Motion',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L09v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Uniform Circular Motion - Direction of the Acceleration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L09v02_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Circular Motion – Acceleration',
                            'lessons' => [
                                [
                                    'name' => 'Circular Motion – Acceleration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L10v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Angular Acceleration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L10v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Angular Position from Angular Acceleration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L10v03_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Newton\'s 2nd Law and Circular Motion',
                            'lessons' => [
                                [
                                    'name' => 'Newton\'s 2nd Law and Circular Motion',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L11v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Car on a Banked Turn',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L11v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Demo: Rotating Bucket',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_Demo_03_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Orbital Circular Motion',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W03PS01_1_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Drag Forces, Constraints and Continuous Systems' => [
                    'tracks' => [
                        [
                            'name' => 'Drag Forces, Constraints and Continuous Systems',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W04Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Pulleys and Constraints',
                            'lessons' => [
                                [
                                    'name' => 'Pulley Problems - Part I, Set up the Equations',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L12v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Pulley Problem - Part II, Constraint Condition',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L12v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Pulley Problem - Part III, Constraints and Virtual Displacement Arguments',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L12v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Pulley Problem - Part IV, Solving the System of Equations',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L12v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - 2 Blocks and 2 Pulleys',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L12v05_360p.mp4',
                                ]
                            ],
                        ],
                        [
                            'name' => 'Massive Rope',
                            'lessons' => [
                                [
                                    'name' => 'Rope Hanging Between Trees',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L13v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Differential Analysis of a Massive Rope',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L13v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Differential Elements',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L13v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Density',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L13v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Demo: Wrapping Friction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_Demo_04_360p.mp4',
                                ],
                                [
                                    'name' => 'Summary of Differential Analysis',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L13v05_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Resistive Forces',
                            'lessons' => [
                                [
                                    'name' => 'Intro to Resistive Forces',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L14v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Resistive Forces - Low Speed Case',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L14v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Resistive Forces - High Speed Case',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L14v03_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Momentum and Impulse' => [
                    'tracks' => [
                        [
                            'name' => 'Momentum and Impulse',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W05Intro_360p.mp4',
                                ],
                                [
                                    'name' => 'Momentum and Impulse',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L15v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Impulse is a Vector',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L15v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Bouncing Ball',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L15v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Momentum of a System of Point Particles',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L15v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Force on a System of Particles',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L15v05_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Conservation of Momentum',
                            'lessons' => [
                                [
                                    'name' => 'Conservation of Momentum',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L16v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Momentum Diagrams',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L16v02_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Center of Mass and Motion of the Center of Mass',
                            'lessons' => [
                                [
                                    'name' => 'Definition of the Center of Mass',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L17v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Center of Mass of 3 Objects',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L17v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Center of Mass of a Continuous System',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L17v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Center of Mass of a Uniform Rod',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L17v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Velocity and Acceleration of the Center of Mass',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L17v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Reduction of a System to a Point Particle',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L17v06_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Continuous Mass Transfer' => [
                    'tracks' => [
                        [
                            'name' => 'Continuous Mass Transfer',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W06Intro_360p.mp4',
                                ],
                                [
                                    'name' => 'Momentum Diagrams',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L19v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Mass Relations',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L19v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Thrust and External Forces',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L19v05_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Relative Velocity and Recoil',
                            'lessons' => [
                                [
                                    'name' => 'Relative Velocity',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L18v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Set up a Recoil Problem',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L18v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Solve for Velocity in the Ground Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L18v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Solve for Velocity in the Moving Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L18v04_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Kinetic Energy and Work' => [
                    'tracks' => [
                        [
                            'name' => 'Kinetic Energy and Work',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W07Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Kinetic Energy and Work in 1D',
                            'lessons' => [
                                [
                                    'name' => 'Kinetic Energy',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L20v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Work by a Constant Force',
                                    'url' => 'hhttps://archive.org/download/MIT8.01F16/MIT8_01F16_L20v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Work by a Non-Constant Force',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L20v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Integrate Acceleration with Respect to Time and Position',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L20v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Work-Kinetic Energy Theorem',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L20v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Power',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L20v06_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Kinetic Energy and Work in 2D and 3D',
                            'lessons' => [
                                [
                                    'name' => 'Scalar Product Properties',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L21v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Scalar Product in Cartesian Coordinates',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L21v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Kinetic Energy as a Scalar Product',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L21v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Work in 2D and 3D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L21v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Work-Kinetic Energy Theorem in 2D and 3D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L21v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Block Going Down a Ramp',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L21v06_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Conservative and Non-Conservative Forces',
                            'lessons' => [
                                [
                                    'name' => 'Path Independence - Gravity',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L22v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Path Dependence - Friction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L22v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Conservative Forces',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L22v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Non-Conservative Forces',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L22v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Summary of Work and Kinetic Energy',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L22v05_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Potential Energy and Energy Conservation' => [
                    'tracks' => [
                        [
                            'name' => 'Potential Energy and Energy Conservation',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W08Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Potential Energy',
                            'lessons' => [
                                [
                                    'name' => 'Introduction to Potential Energy',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L23v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Potential Energy of Gravity near the Surface of the Earth',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L23v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Potential Energy Reference State',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L23v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Potential Energy of a Spring',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L23v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Potential Energy of Gravitation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L23v05_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Conservation of Energy',
                            'lessons' => [
                                [
                                    'name' => 'Mechanical Energy and Energy Conservation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L24v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Energy State Diagrams',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L24v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Block Sliding Down Circular Slope',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L24v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Newton\'s 2nd Law and Energy Conservation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L24v04_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Potential Energy Diagrams',
                            'lessons' => [
                                [
                                    'name' => 'Force is the Derivative of Potential',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L25v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Stable and Unstable Equilibrium Points',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L25v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Reading Potential Energy Diagrams',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L25v03_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Collision Theory' => [
                    'tracks' => [
                        [
                            'name' => 'Collision Theory',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W09Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Types of Collision',
                            'lessons' => [
                                [
                                    'name' => 'Momentum in Collisions',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L26v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Kinetic Energy in Collisions',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L26v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Totally Inelastic Collisions',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L26v03_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Elastic Collisions',
                            'lessons' => [
                                [
                                    'name' => 'Worked Example: Elastic 1D Collision',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L27v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Relative Velocity in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L27v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Gravity at the Surface of the Earth: The Value of g.',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L05v03_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Contact Forces',
                            'lessons' => [
                                [
                                    'name' => 'Contact Forces',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L06v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Relative Velocity in 1D',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L27v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Kinetic Energy and Momentum Equation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L27v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example: Elastic 1D Collision Again',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L27v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Gravitational Slingshot',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L27v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Collisions',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L27v06_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Center of Mass Reference Frame',
                            'lessons' => [
                                [
                                    'name' => 'Position in the CM Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_CMframe1_360p.mp4',
                                ],
                                [
                                    'name' => 'Relative Velocity is Independent of Reference Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_CMframe2_360p.mp4',
                                ],
                                [
                                    'name' => '1D Elastic Collision Velocities in CM Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_CMframe3_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example: 1D Elastic Collision in CM',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_CMframe4_360p.mp4',
                                ],
                                [
                                    'name' => 'Kinetic Energy in Different Reference Frames',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_CMframe5_360p.mp4',
                                ],
                                [
                                    'name' => 'Kinetic Energy in the CM Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_CMframe6_360p.mp4',
                                ],
                                [
                                    'name' => 'Change in the Kinetic Energy',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_DD_CMframe7_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Rotational Motion' => [
                    'tracks' => [
                        [
                            'name' => 'Newton\'s Laws',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W10Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Motion of a Rigid Body',
                            'lessons' => [
                                [
                                    'name' => 'Rigid Bodies',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L28v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Introduction to Translation and Rotation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L28v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Review of Angular Velocity and Acceleration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L28v03_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Moment of Inertia',
                            'lessons' => [
                                [
                                    'name' => 'Kinetic Energy of Rotation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L29v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Moment of Inertia of a Rod',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L29v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Moment of Inertia of a Disc',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L29v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Parallel Axis Theorem',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L29v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Moment of Inertia of a Sphere',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L29DD01_360p.mp4',
                                ],
                                [
                                    'name' => 'Derivation of the Parallel Axis Theorem',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L29DD02_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Torque',
                            'lessons' => [
                                [
                                    'name' => 'Introduction to Torque and Rotational Dynamics',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L30v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Cross Product',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L30v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Cross Product in Cartesian Coordinates',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L30v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Torque',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L30v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Torque from Gravity',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L30v05_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Rotational Dynamics',
                            'lessons' => [
                                [
                                    'name' => 'Relationship between Torque and Angular Acceleration',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L31v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Internal Torques Cancel in Pairs',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L31v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Find the Moment of Inertia of a Disc from a Falling Mass',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L31v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Massive Pulley Problems',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L31v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Two Blocks and a Pulley Using Energy',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L31v06_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Angular Momentum' => [
                    'tracks' => [
                        [
                            'name' => 'Angular Momentum',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W11Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Angular Momentum of a Point Particle',
                            'lessons' => [
                                [
                                    'name' => 'Angular Momentum of a Point Particle',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L32v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Calculating Angular Momentum',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L32v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Angular Momentum About Different Points',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L32v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Angular Momentum of Circular Motion',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L32v04_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Angular Momentum of a Rigid Body about a Fixed Axis',
                            'lessons' => [
                                [
                                    'name' => 'Worked Example - Angular Momentum of 2 Rotating Point Particles',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L33v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Angular Momentum of a Symmetric Object',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L33v02_360p.mp4',
                                ],
                                [
                                    'name' => 'If Momentum is Zero then Angular Momentum is Independent of Origin',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L33v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Kinetic Energy of a Symmetric Object',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L33v03_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Torque and Angular Impulse',
                            'lessons' => [
                                [
                                    'name' => 'Torque Causes Angular Momentum to Change - Point Particle',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L34v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Torque Causes Angular Momentum to Change - System of Particles',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L34v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Angular Impulse',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L34v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Demo: Bicycle Wheel',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_Demo_34_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Particle Hits Pivoted Ring',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L34v04_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Rotations and Translation' => [
                    'tracks' => [
                        [
                            'name' => 'Rotations and Translation',
                            'lessons' => [
                                [
                                    'name' => 'Introduction',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W12Intro_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Rolling Kinematics',
                            'lessons' => [
                                [
                                    'name' => 'Translation and Rotation of a Wheel',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L35v01_360p.mp4',
                                ],
                                [
                                    'name' => 'Rolling Wheel in the Center of Mass Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L35v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Rolling Wheel in the Ground Frame',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L35v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Rolling Without Slipping, Slipping, and Skidding',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L35v04_360p.mp4',
                                ],
                                [
                                    'name' => 'Contact Point of a Wheel Rolling Without Slipping',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L35v05_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Rolling Dynamics',
                            'lessons' => [
                                [
                                    'name' => 'Friction on a Rolling Wheel',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L36v02_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Wheel Rolling Without Slipping Down Inclined Plane - Torque Method',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L36v03_360p.mp4',
                                ],
                                [
                                    'name' => 'Spool Demo',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W12spooldemo_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Yoyo Pulled Along the Ground',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L36v05_360p.mp4',
                                ],
                                [
                                    'name' => 'Analyze Force and Torque in Translation and Rotation Problems',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L36v04_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Rolling Kinetic Energy and Angular Momentum',
                            'lessons' => [
                                [
                                    'name' => 'Kinetic Energy of Translation and Rotation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L35v06_360p.mp4',
                                ],
                                [
                                    'name' => 'Worked Example - Wheel Rolling Without Slipping Down Inclined Plane',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L35v07_360p.mp4',
                                ],
                                [
                                    'name' => 'Angular Momentum of Translation and Rotation',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_L36v01_360p.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Gyroscopes',
                            'lessons' => [
                                [
                                    'name' => 'Free Body Diagrams, Torque, and Rotating Vectors',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W12DD01_360p.mp4',
                                ],
                                [
                                    'name' => 'Precessional Angular Velocity and Titled Gyroscopes',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W12DD02_360p.mp4',
                                ],
                                [
                                    'name' => 'Nutation and Total Angular Momentum',
                                    'url' => 'https://archive.org/download/MIT8.01F16/MIT8_01F16_W12DD03_360p.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Primary Skill' => 'classical mechanics,Space and time,straight-line kinematics,motion in a plane,forces and equilibrium,experimental basis of Newton\'s laws,particle dynamics,universal gravitation,collisions and conservation laws,work and potential energy,vibrational motion,conservative forces,inertial forces and non-inertial frames,central force motions,rigid bodies and rotational dynamics',

        ];

        $mathTracks = [
            'Linear Algebra' => [
                'The Four Subspaces' => [
                    'tracks' => [
                        [
                            'name' => 'The Geometry of Linear Equations',
                            'lessons' => [
                                [
                                    'name' => 'The Geometry of Linear Equations',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/01.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'An Overview of Linear Algebra',
                            'lessons' => [
                                [
                                    'name' => 'An Overview of Linear Algebra',
                                    'url' => 'http://www.archive.org/download/MIT18.085F08/ocw-18.085-f08-rec01_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Elimination with Matrices',
                            'lessons' => [
                                [
                                    'name' => 'Elimination with Matrices',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/02.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Multiplication and Inverse Matrices',
                            'lessons' => [
                                [
                                    'name' => 'Multiplication and Inverse Matrices',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/03.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Factorization into A = LU',
                            'lessons' => [
                                [
                                    'name' => 'Factorization into A = LU',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/04.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Transposes, Permutations, Vector Spaces',
                            'lessons' => [
                                [
                                    'name' => 'Transposes, Permutations, Vector Spaces',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/05.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Column Space and Nullspace',
                            'lessons' => [
                                [
                                    'name' => 'Column Space and Nullspace',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/06.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Solving Ax = 0: Pivot Variables, Special Solutions',
                            'lessons' => [
                                [
                                    'name' => 'Solving Ax = 0: Pivot Variables, Special Solutions',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/07.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Solving Ax = b: Row Reduced Form R',
                            'lessons' => [
                                [
                                    'name' => 'Solving Ax = b: Row Reduced Form R',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/08.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Independence, Basis and Dimension',
                            'lessons' => [
                                [
                                    'name' => 'Independence, Basis and Dimension',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/09.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'The Four Fundamental Subspaces',
                            'lessons' => [
                                [
                                    'name' => 'The Four Fundamental Subspaces',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/10.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Matrix Spaces; Rank 1; Small World Graphs',
                            'lessons' => [
                                [
                                    'name' => 'Matrix Spaces; Rank 1; Small World Graphs',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/11.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Graphs, Networks, Incidence Matrices',
                            'lessons' => [
                                [
                                    'name' => 'Graphs, Networks, Incidence Matrices',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/12.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Least Squares, Determinants and Eigenvalues' => [
                    'tracks' => [
                        [
                            'name' => 'Orthogonal Vectors and Subspaces',
                            'lessons' => [
                                [
                                    'name' => 'Orthogonal Vectors and Subspaces',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/14.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Projections onto Subspaces',
                            'lessons' => [
                                [
                                    'name' => 'Projections onto Subspaces',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/15.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Projection Matrices and Least Squares',
                            'lessons' => [
                                [
                                    'name' => 'Projection Matrices and Least Squares',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/16.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Orthogonal Matrices and Gram-Schmidt',
                            'lessons' => [
                                [
                                    'name' => 'Orthogonal Matrices and Gram-Schmidt',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/17.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Properties of Determinants',
                            'lessons' => [
                                [
                                    'name' => 'Properties of Determinants',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/18.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Determinant Formulas and Cofactors',
                            'lessons' => [
                                [
                                    'name' => ' Determinant Formulas and Cofactors',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/19.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Cramer\'s Rule, Inverse Matrix and Volume',
                            'lessons' => [
                                [
                                    'name' => 'Cramer\'s Rule, Inverse Matrix and Volume',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/20.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Eigenvalues and Eigenvectors',
                            'lessons' => [
                                [
                                    'name' => 'Eigenvalues and Eigenvectors',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/21.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Diagonalization and Powers of A',
                            'lessons' => [
                                [
                                    'name' => 'Diagonalization and Powers of A',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/22.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Differential Equations and exp(At)',
                            'lessons' => [
                                [
                                    'name' => 'Differential Equations and exp(At)',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/23.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Markov Matrices; Fourier Series',
                            'lessons' => [
                                [
                                    'name' => 'Markov Matrices; Fourier Series',
                                    'url' => 'http://www.archive.org/download/MIT18.06S05_MP4/24.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
             ],
            'Primary Skill' => 'matrix theory,linear algebra,systems of equations,vector spaces,determinants,eigenvalues,similarity,positive definite matrices,least-squares approximations,stability of differential equations,networks,Fourier transforms,Markov processes,Linear Algebra',
        ];

        $chemistryTracks = [
            'Principles of Chemical Science' => [
                'The Atom' => [
                    'tracks' => [
                        [
                            'name' => 'The Importance of Chemical Principles',
                            'lessons' => [
                                [
                                    'name' => 'The Importance of Chemical Principles',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L01_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Atomic Structure',
                            'lessons' => [
                                [
                                    'name' => 'Atomic Structure',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L02_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Wave-Particle Duality of Light',
                            'lessons' => [
                                [
                                    'name' => 'Wave-Particle Duality of Light',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L03_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Wave-Particle Duality of Matter; Schrödinger Equation',
                            'lessons' => [
                                [
                                    'name' => 'Wave-Particle Duality of Matter; Schrödinger Equation',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L04_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Hydrogen Atom Energy Levels',
                            'lessons' => [
                                [
                                    'name' => 'Hydrogen Atom Energy Levels',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L05_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Hydrogen Atom Wavefunctions',
                            'lessons' => [
                                [
                                    'name' => 'Hydrogen Atom Wavefunctions',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L06_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Multi-electron Atoms',
                            'lessons' => [
                                [
                                    'name' => 'Multi-electron Atoms',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L07_300k.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Chemical Bonding and Structure' => [
                    'tracks' => [
                        [
                            'name' => 'The Periodic Table and Periodic Trends',
                            'lessons' => [
                                [
                                    'name' => 'The Periodic Table and Periodic Trends',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L08_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Periodic Table; Ionic and Covalent Bonds',
                            'lessons' => [
                                [
                                    'name' => 'Periodic Table; Ionic and Covalent Bonds',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L09_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Introduction to Lewis Structures',
                            'lessons' => [
                                [
                                    'name' => 'Introduction to Lewis Structures',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L10_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Lewis Structures: Breakdown of the Octet Rule',
                            'lessons' => [
                                [
                                    'name' => 'Lewis Structures: Breakdown of the Octet Rule',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L11_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'The Shapes of Molecules: VSEPR Theory',
                            'lessons' => [
                                [
                                    'name' => 'The Shapes of Molecules: VSEPR Theory',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L12_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Molecular Orbital Theory',
                            'lessons' => [
                                [
                                    'name' => 'Molecular Orbital Theory',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L13_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Valence Bond Theory and Hybridization',
                            'lessons' => [
                                [
                                    'name' => 'Valence Bond Theory and Hybridization',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L14_300k.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Thermodynamics and Chemical Equilibrium' => [
                    'tracks' => [
                        [
                            'name' => 'Thermodynamics: Bond and Reaction Enthalpies',
                            'lessons' => [
                                [
                                    'name' => 'Thermodynamics: Bond and Reaction Enthalpies',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L15_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Thermodynamics: Gibbs Free Energy and Entropy',
                            'lessons' => [
                                [
                                    'name' => 'Thermodynamics: Gibbs Free Energy and Entropy',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L16_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Thermodynamics: Now What Happens When You Heat It Up?',
                            'lessons' => [
                                [
                                    'name' => 'Thermodynamics: Now What Happens When You Heat It Up?',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L17_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Introduction to Chemical Equilibrium',
                            'lessons' => [
                                [
                                    'name' => 'Introduction to Chemical Equilibrium',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L18_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Chemical Equilibrium: Le Châtelier’s Principle',
                            'lessons' => [
                                [
                                    'name' => 'Chemical Equilibrium: Le Châtelier’s Principle',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L19_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Solubility and Acid-Base Equilibrium',
                            'lessons' => [
                                [
                                    'name' => 'Solubility and Acid-Base Equilibrium',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L20_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Acid-Base Equilibrium: Is MIT Water Safe to Drink?',
                            'lessons' => [
                                [
                                    'name' => 'Acid-Base Equilibrium: Is MIT Water Safe to Drink?',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L21_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Acid-Base Equilibrium: Salt Solutions and Buffers',
                            'lessons' => [
                                [
                                    'name' => 'Acid-Base Equilibrium: Salt Solutions and Buffers',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L22_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Acid-Base Titrations Part I',
                            'lessons' => [
                                [
                                    'name' => 'Acid-Base Titrations Part I',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L23_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Acid-Base Titrations Part II',
                            'lessons' => [
                                [
                                    'name' => 'Acid-Base Titrations Part II',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L24_300k.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Transition Metals and Oxidation Reduction Reactions' => [
                    'tracks' => [
                        [
                            'name' => 'Oxidation-Reduction and Electrochemical Cells',
                            'lessons' => [
                                [
                                    'name' => 'Oxidation-Reduction and Electrochemical Cells',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L25_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Chemical and Biological Oxidations',
                            'lessons' => [
                                [
                                    'name' => 'Chemical and Biological Oxidations',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L26_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Introduction to Transition Metals',
                            'lessons' => [
                                [
                                    'name' => 'Introduction to Transition Metals',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L27_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Transition Metals: Crystal Field Theory Part I',
                            'lessons' => [
                                [
                                    'name' => 'Transition Metals: Crystal Field Theory Part I',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L28_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Transition Metals: Crystal Field Theory Part II',
                            'lessons' => [
                                [
                                    'name' => 'Chemical Equilibrium: Le Châtelier’s Principle',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L29_300k.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
                'Chemical Kinetics' => [
                    'tracks' => [
                        [
                            'name' => 'Kinetics: Rate Laws',
                            'lessons' => [
                                [
                                    'name' => 'Kinetics: Rate Laws',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L30_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Nuclear Chemistry and Chemical Kinetics',
                            'lessons' => [
                                [
                                    'name' => 'Nuclear Chemistry and Chemical Kinetics',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L31_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Kinetics: Reaction Mechanisms',
                            'lessons' => [
                                [
                                    'name' => 'Kinetics: Reaction Mechanisms',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L32_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Kinetics and Temperature',
                            'lessons' => [
                                [
                                    'name' => 'Kinetics and Temperature',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L33_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Kinetics: Catalysts',
                            'lessons' => [
                                [
                                    'name' => 'Kinetics: Catalysts',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L34_300k.mp4',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Applying Chemical Principles',
                            'lessons' => [
                                [
                                    'name' => 'Applying Chemical Principles',
                                    'url' => 'https://archive.org/download/MIT5.111F14/MIT5_111F14_L35_300k.mp4',
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            'Primary Skill' => 'chemistry,biological molecules,inorganic molecules,organic molecules,atomic structure,molecular electronic structure,thermodynamics,acid-base equilibrium,redox equilibrium,chemical kinetics,catalysis,Inorganic Chemistry,Organic Chemistry,Physical Chemistry',
        ];
    }
}

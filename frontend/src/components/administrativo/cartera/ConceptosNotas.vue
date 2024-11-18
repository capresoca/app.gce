<template>
	<v-card>
		<v-toolbar extended>
			<v-fab-transition>
				<v-btn
				color="pink"
				fab
				dark
				small
				absolute
				bottom
				right
				@click="dialogSave=true"
				>
					<v-icon>add</v-icon>
				</v-btn>
			</v-fab-transition>
		</v-toolbar>
		<h1>Conceptos de notas</h1>
		<v-container fluid grid-list-xl>
			<v-layout row wrap>
				<v-flex xs12 sm6 md4>
					<v-text-field
					v-model="search"
					append-icon="search"
					label="Buscar"
					@input="buscar"
					/>
				</v-flex>
			</v-layout>
			<v-layout row wrap>
				<v-flex xs12 pa-0>
					<v-data-table
					:headers="headers"
					:items="desserts"
					class="elevation-1"
					>
						<template slot="headerCell" slot-scope="props">
							<v-tooltip bottom>
								<span slot="activator">
									{{ props.header.text }}
								</span>
								<span>
									{{ props.header.text }}
								</span>
							</v-tooltip>
						</template>
						<template slot="items" slot-scope="props">
							<td>{{ props.item.name }}</td>
							<td class="text-xs-right">{{ props.item.calories }}</td>
							<td class="text-xs-right">{{ props.item.fat }}</td>
							<td class="text-xs-right">{{ props.item.carbs }}</td>
							<td class="text-xs-right">{{ props.item.protein }}</td>
							<td class="text-xs-right">{{ props.item.iron }}</td>
						</template>
					</v-data-table>
				</v-flex>
			</v-layout>
		</v-container>
		<v-container>
			<div class="text-xs-center">
				<v-dialog
      		v-model="dialogSave"
				>
					<v-card>
						<v-card-title
						class="headline grey lighten-2"
						primary-title
						>
							Registrar conceptos de notas
						</v-card-title>
						<v-card-text>
							<v-container fluid grid-list-xl >
								<v-layout row wrap justify-space-between>
									<v-flex xs12 sm4 md4 lg4>
										<v-text-field label="Codigo" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm8 md8 lg8 align-end>
										<v-btn color="success">Presupuestos</v-btn>
									</v-flex>
								</v-layout>
								<v-divider></v-divider>
								<v-layout row wrap>
									<v-flex xs12 sm4 md4 lg4>
										<v-text-field label="Codigo municipio" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm8 md8 lg8>
										<v-text-field label="Nombre Municipio" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm4 md4 lg4>
										<v-text-field label="Codigo departamento" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm8 md8 lg8>
										<v-text-field label="Nombre departamento" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md12 lg12>
										<v-text-field label="Direccion" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md4 lg4>
										<v-text-field label="Telefono #1" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md4 lg4>
										<v-text-field label="Telefono #2" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md4 lg4>
										<v-text-field label="Fax" required></v-text-field>
									</v-flex>
								</v-layout>
								<v-divider></v-divider>
								<v-layout row wrap>
									<v-flex xs12 sm12 md4 lg4>
										<v-text-field label="Codigo del vendedor" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md8 lg8>
										<v-text-field label="Vendedor" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md4 lg4>
										<v-text-field label="N° dias de plazo" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md4 lg4>
										<v-text-field label="Cupo maximo" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md4 lg4>
										<v-text-field label="% Descuento" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md12 lg12>
										<v-text-field label="Contacto" required></v-text-field>
									</v-flex>
									<v-flex xs12 sm12 md6 lg6>
										Maneja IVA
										<v-radio-group :column="false">
											<v-radio
											key="1"
											label="Si"
											value="si"
											></v-radio>
											<v-radio
											key="2"
											label="No"
											value="no"
											></v-radio>
										</v-radio-group>
									</v-flex>
									<v-flex xs12 sm12 md6 lg6>
										Tipo cliente
										<v-radio-group :column="false">
											<v-radio
											key="1"
											label="Clase A"
											value="a"
											></v-radio>
											<v-radio
											key="2"
											label="Clase B"
											value="b"
											></v-radio>
											<v-radio
											key="3"
											label="Clase C"
											value="c"
											></v-radio>
										</v-radio-group>
									</v-flex>
									<v-flex xs12 sm12 md12 lg12>
										Estado
										<v-radio-group :column="false">
											<v-radio
											key="1"
											label="Activo"
											value="activo"
											></v-radio>
											<v-radio
											key="2"
											label="Inactivo"
											value="inactivo"
											></v-radio>
										</v-radio-group>
									</v-flex>
								</v-layout>
							</v-container>
						</v-card-text>
						<v-divider></v-divider>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn
							color="primary"
							flat
							@click="dialogSave = false"
							>
								Guardar
							</v-btn>
							<v-btn
							color="primary"
							flat
							@click="dialogSave = false"
							>
								Cerrar
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</div>
		</v-container>
	</v-card>
</template>

<script>
  export default {
    name: 'ConceptosNotas',
    data () {
      return {
        search: '',
        dialogSave: false,
        headers: [
          {
            text: 'Dessert (100g serving)',
            align: 'left',
            sortable: false,
            value: 'name'
          },
          { text: 'Calories', value: 'calories' },
          { text: 'Fat (g)', value: 'fat' },
          { text: 'Carbs (g)', value: 'carbs' },
          { text: 'Protein (g)', value: 'protein' },
          { text: 'Iron (%)', value: 'iron' }
        ],
        desserts: [
          {
            value: false,
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%'
          },
          {
            value: false,
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
            iron: '1%'
          },
          {
            value: false,
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
            iron: '7%'
          },
          {
            value: false,
            name: 'Cupcake',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
            iron: '8%'
          },
          {
            value: false,
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
            iron: '16%'
          },
          {
            value: false,
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
            iron: '0%'
          },
          {
            value: false,
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
            iron: '2%'
          },
          {
            value: false,
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
            iron: '45%'
          },
          {
            value: false,
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
            iron: '22%'
          },
          {
            value: false,
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
            iron: '6%'
          }
        ]
      }
    }
  }
</script>

<style scoped>

</style>
